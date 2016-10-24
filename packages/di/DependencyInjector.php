<?php namespace axis\di;

use axis\events\Event;
use axis\exceptions\CannotInstantiateException;
use axis\exceptions\UnexpectedVariableTypeException;
use axis\exceptions\UnresolvedClassException;
use axis\helpers\StringHelper as Str;
use axis\specification\di\DependencyDefinitionInterface;
use axis\specification\di\DependencyInjectorInterface;
use ReflectionClass;
use ReflectionMethod;

class DependencyInjector implements DependencyInjectorInterface
{
    const ROOT_SCOPE = 'rootScope';

    public $scopeDelimiter = ':';

    /**
     * @var DependencyDefinitionInterface[]
     */
    private $_definitions = [];
    private $_singletons;
    private $_dependencyDefinitionClass;
    private $_currentScope = self::ROOT_SCOPE;

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
            return $this->addDependencyDefinition($contract, $agent);
        } else if (is_string($agent) && is_object($agent)) {
            $this->addSingleton($contract, $agent);
            return $this->addDependencyDefinition($contract, get_class($agent));
        } else if (is_string($agent) && is_callable($agent)) {
            return $this->set($contract, call_user_func($agent, $this));
        } else if (is_string($contract) && is_null($agent)) {
            return $this->set($contract, $contract);
        } else if (is_object($contract) && is_null($agent)) {
            return $this->set(get_class($contract), $contract);
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
    public function get(string $contract, callable $beforeInstantiate = null)
    {
        list($scope, $definition) = $this->getDefinition($contract);
        if (!$definition instanceof DependencyDefinitionInterface) {
            throw new \InvalidArgumentException('Passed contract was not registered to create it: ' . $contract);
        }
        if ($definition->isSingleton()) {
            if (!isset($this->_singletons[$scope][$contract])) {
                $agentInstance = $this->resolve($definition, $beforeInstantiate);
                $this->_singletons[$scope][$contract] = $agentInstance;
            }
            return $this->_singletons[$scope][$contract];
        } else {
            return $this->resolve($definition, $beforeInstantiate);
        }
    }

    /**
     * @param string $contract
     * @return array|null
     */
    public function getDefinition(string $contract)
    {
        foreach (Str::cutByPiece($this->_currentScope, ':') as $scope) {
            if (isset($this->_definitions[$scope][$contract])) {
                return [$scope, $this->_definitions[$scope][$contract]];
            }
        }
        return [null, null];
    }

    public function hasInstantiatedSingleton($contract)
    {
        return isset($this->_singletons[$this->_currentScope][$contract]);
    }

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function getSingleton(string $contract, callable $beforeInstantiate = null)
    {
        list($scope, $definition) = $this->getDefinition($contract);
        if (!$definition instanceof DependencyDefinitionInterface) {
            throw new \InvalidArgumentException('Passed contract was not registered to create it: ' . $contract);
        }
        if ($definition->isSingleton()) {
            return $this->get($contract);
        } else {
            if (!isset($this->_singletons[$scope][$contract])) {
                $agentInstance = $this->get($contract);
                $this->_singletons[$scope][$contract] = $agentInstance;
            }
            return $this->_singletons[$scope][$contract];
        }
    }

    /**
     * @param string $contract
     * @return mixed
     */
    public function has(string $contract) : bool
    {
        foreach (Str::cutByPiece($this->_currentScope, ':') as $remainedPiece) {
            if (isset($this->_definitions[$remainedPiece][$contract])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string|DependencyDefinitionInterface $agentDefinition
     * @param callable|null $beforeInstantiate
     * @return mixed
     * @throws UnexpectedVariableTypeException
     */
    public function resolve($agentDefinition, callable $beforeInstantiate = null)
    {
        if (is_string($agentDefinition)) {
            $definition = $this->createDependencyDefinition($agentDefinition);
            return $this->resolve($definition, $beforeInstantiate);
        } else if ($agentDefinition instanceof DependencyDefinitionInterface) {
            $classInspector = new ReflectionClass($agentDefinition->getAgent());
            $constructorInspector = $classInspector->getConstructor();
            if ($constructorInspector) {
                if ($agentDefinition->isArgumentsMap()) {
                    $arguments = $this->resolveMapArguments($constructorInspector, $agentDefinition);
                } else {
                    $arguments = $this->resolveListArguments($constructorInspector, $agentDefinition);
                }
                $beforeCreateEvent = new Event(
                    DependencyDefinitionInterface::EVENT_BEFORE_CREATE,
                    $arguments,
                    $agentDefinition);
                if (is_callable($beforeInstantiate)) {
                    call_user_func($beforeInstantiate, $arguments);
                }
                $agentDefinition->emitEvent($beforeCreateEvent);
                $agentInstance = $classInspector->newInstanceArgs($arguments);
            } else {
                $beforeCreateEvent = new Event(
                    DependencyDefinitionInterface::EVENT_BEFORE_CREATE,
                    [],
                    $agentDefinition);
                $agentDefinition->emitEvent($beforeCreateEvent);
                if (is_callable($beforeInstantiate)) {
                    call_user_func($beforeInstantiate, []);
                }
                $agentInstance = $classInspector->newInstance();
            }
            $afterCreateEvent = new Event(
                DependencyDefinitionInterface::EVENT_AFTER_CREATE,
                $agentInstance,
                $agentDefinition);
            $agentDefinition->emitEvent($afterCreateEvent);
            return $agentInstance;
        } else {
            throw new UnexpectedVariableTypeException($agentDefinition);
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
    private function createDependencyDefinition($agent) : DependencyDefinitionInterface
    {
        $dependencyDefinitionClass = $this->_dependencyDefinitionClass;
        return new $dependencyDefinitionClass($agent);
    }

    private function addDependencyDefinition(string $contract, $agent) : DependencyDefinitionInterface
    {
        if (is_string($agent)) {
            $agent = $this->createDependencyDefinition($agent);
        }
        $this->_definitions[$this->_currentScope][$contract] = $agent;
        return $agent;
    }

    private function addSingleton(string $contract, $obj)
    {
        $this->_singletons[$this->_currentScope][$contract] = $obj;
    }

    /**
     * @param string $scope
     * @param callable|null $scopeClosure
     * @return void
     */
    public function scope(string $scope, callable $scopeClosure)
    {
        $oldScope = $this->_currentScope;
        $this->_currentScope = ($oldScope ? ($oldScope . ':') : '') . $scope;
        call_user_func($scopeClosure, $this, $scope);
        $this->_currentScope = $oldScope;
    }
}