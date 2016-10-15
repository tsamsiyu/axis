<?php namespace axis\di;

use axis\events\Event;
use axis\exceptions\CannotInstantiateException;
use axis\exceptions\UnexpectedVariableTypeException;
use axis\exceptions\UnresolvedClassException;
use axis\specification\di\DependencyDefinitionInterface;
use axis\specification\di\DependencyInjectorInterface;
use ReflectionClass;
use ReflectionMethod;

class DependencyInjector implements DependencyInjectorInterface
{
    /**
     * @var DependencyDefinitionInterface[]
     */
    private $_definitions = [];
    private $_singletons;
    private $_dependencyDefinitionClass;

    public function __construct($dependencyDefinitionClass = DependencyDefinition::class)
    {
        if (!is_subclass_of($dependencyDefinitionClass, DependencyDefinitionInterface::class)) {
            throw new \InvalidArgumentException('Passed argument must be implementation of DependencyDefinitionInterface');
        }
        $this->_dependencyDefinitionClass = $dependencyDefinitionClass;
    }

    /**
     * @param string $contract
     * @param string $agent
     * @return DependencyDefinitionInterface
     * @throws UnexpectedVariableTypeException
     */
    public function set($contract, $agent = null)
    {
        if (is_string($contract) && is_string($agent)) {
            $definition = $this->createDependencyDefinition($agent);
            $this->_definitions[$contract] = $definition;
            return $definition;
        } else if (is_string($agent) && is_object($agent)) {
            $definition = $this->createDependencyDefinition(get_class($agent));
            $this->_definitions[$contract] = $definition;
            $this->_singletons[$contract] = $agent;
            return $definition;
        } else if (is_string($agent) && is_callable($agent)) {
            return $this->set($contract, call_user_func($agent, $this));
        } else if (is_string($contract) && is_null($agent)) {
            return $this->set($contract, $contract);
        } else if (is_object($contract) && is_null($agent)) {
            $class = get_class($contract);
            $definition = $this->createDependencyDefinition($class);
            $this->_definitions[$class] = $definition;
            $this->_singletons[$class] = $contract;
            return $definition;
        } else if (is_callable($contract) && is_null($agent)) {
            return $this->set(call_user_func($contract, $this));
        } else {
            throw new \InvalidArgumentException("Invalid combination of arguments");
        }
    }

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function get(string $contract, $beforeInstantiate = null)
    {
        if (!$this->has($contract)) {
            throw new \InvalidArgumentException('Passed contract was not registered to create it: ' . $contract);
        }
        $definition = $this->getDefinition($contract);
        if ($definition->isSingleton()) {
            if (!$this->hasInstantiatedSingleton($contract)) {
                $agentInstance = $this->resolve($definition, $beforeInstantiate);
                $this->_singletons[$contract] = $agentInstance;
            }
            return $this->_singletons[$contract];
        } else {
            return $this->resolve($definition, $beforeInstantiate);
        }
    }

    public function getDefinition(string $contract)
    {
        return $this->_definitions[$contract];
    }

    public function hasInstantiatedSingleton($contract)
    {
        return isset($this->_singletons[$contract]);
    }

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function getSingleton(string $contract, $beforeInstantiate = null)
    {
        if (!$this->has($contract)) {
            throw new \InvalidArgumentException('Passed contract was not registered to create it: ' . $contract);
        }
        $definition = $this->getDefinition($contract);
        if ($definition->isSingleton()) {
            return $this->get($contract);
        } else {
            if (!$this->hasInstantiatedSingleton($contract)) {
                $agentInstance = $this->get($contract);
                $this->_singletons[$contract] = $agentInstance;
            }
            return $this->_singletons[$contract];
        }
    }

    /**
     * @param string $contract
     * @return mixed
     */
    public function has(string $contract)
    {
        return isset($this->_definitions[$contract]);
    }

    /**
     * @param string|DependencyDefinitionInterface $agentOrDefinition
     * @param null $beforeInstantiate
     * @return mixed
     * @throws UnexpectedVariableTypeException
     */
    public function resolve($agentOrDefinition, $beforeInstantiate = null)
    {
        if (is_string($agentOrDefinition)) {
            $definition = $this->createDependencyDefinition($agentOrDefinition);
            return $this->resolve($definition, $beforeInstantiate);
        } else if ($agentOrDefinition instanceof DependencyDefinitionInterface) {
            $classInspector = new ReflectionClass($agentOrDefinition->getAgent());
            $constructorInspector = $classInspector->getConstructor();
            if ($constructorInspector) {
                if ($agentOrDefinition->isArgumentsMap()) {
                    $arguments = $this->resolveMapArguments($constructorInspector, $agentOrDefinition);
                } else {
                    $arguments = $this->resolveListArguments($constructorInspector, $agentOrDefinition);
                }
                $beforeCreateEvent = new Event(
                    DependencyDefinitionInterface::EVENT_BEFORE_CREATE,
                    $arguments,
                    $agentOrDefinition);
                if (is_callable($beforeInstantiate)) {
                    call_user_func($beforeInstantiate, $arguments);
                }
                $agentOrDefinition->emitEvent($beforeCreateEvent);
                $agentInstance = $classInspector->newInstanceArgs($arguments);
            } else {
                $beforeCreateEvent = new Event(
                    DependencyDefinitionInterface::EVENT_BEFORE_CREATE,
                    [],
                    $agentOrDefinition);
                $agentOrDefinition->emitEvent($beforeCreateEvent);
                if (is_callable($beforeInstantiate)) {
                    call_user_func($beforeInstantiate, []);
                }
                $agentInstance = $classInspector->newInstance();
            }
            $afterCreateEvent = new Event(
                DependencyDefinitionInterface::EVENT_AFTER_CREATE,
                $agentInstance,
                $agentOrDefinition);
            $agentOrDefinition->emitEvent($afterCreateEvent);
            return $agentInstance;
        } else {
            throw new UnexpectedVariableTypeException($agentOrDefinition);
        }
    }

    /**
     * @param ReflectionMethod $constructorInspector
     * @param DependencyDefinitionInterface $definition
     * @return array|null
     * @throws CannotInstantiateException
     * @throws UnresolvedClassException
     */
    protected function resolveListArguments(ReflectionMethod $constructorInspector, DependencyDefinitionInterface $definition)
    {
        $expectedArguments = $constructorInspector->getParameters();
        return $this->resolveMissingArguments($expectedArguments, $definition->getArguments(), $definition);
    }

    protected function resolveMapArguments(ReflectionMethod $constructorInspector, DependencyDefinitionInterface $definition)
    {
        $expectedArguments = $constructorInspector->getParameters();
        $mapArguments = $definition->getArguments();
        $arguments = [];
        foreach ($expectedArguments as $expectedArgument) {
            if (isset($mapArguments[$expectedArgument->name])) {
                $arguments[$expectedArgument->getPosition()] = $mapArguments[$expectedArgument->name];
            }
        }
        return $this->resolveMissingArguments($expectedArguments, $arguments, $definition);
    }

    /**
     * @param \ReflectionParameter[] $expectedArguments
     * @param array $arguments
     * @param DependencyDefinitionInterface $definition
     * @return array
     * @throws CannotInstantiateException
     * @throws UnresolvedClassException
     */
    protected function resolveMissingArguments($expectedArguments, array $arguments, DependencyDefinitionInterface $definition)
    {
        foreach ($expectedArguments as $position => $expectedArgument) {
            if (!isset($arguments[$position])) {
                $expectedArgumentClass = $expectedArgument->getClass();
                if (!$expectedArgumentClass) {
                    if (!$expectedArgument->isOptional()) {
                        throw new CannotInstantiateException($definition->getAgent(), 'is not enough arguments passed');
                    } else {
                        $arguments[$position] = $expectedArgument->getDefaultValue();
                    }
                } else {
                    if ($this->has($expectedArgumentClass->name)) {
                        $dependency = $this->get($expectedArgumentClass->name);
                        $arguments[] = $dependency;
                    } elseif (!$expectedArgument->isOptional()) {
                        throw new UnresolvedClassException($expectedArgumentClass->name);
                    } else {
                        $arguments[$position] = $expectedArgument->getDefaultValue();
                    }
                }
            }
        }
        return $arguments;
    }

    /**
     * @param $agent
     * @return DependencyDefinitionInterface
     */
    private function createDependencyDefinition($agent)
    {
        $dependencyDefinitionClass = $this->_dependencyDefinitionClass;
        return new $dependencyDefinitionClass($agent);
    }
}