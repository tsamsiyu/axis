<?php namespace axis\di;

use axis\exceptions\CannotInstantiateException;
use axis\exceptions\UnexpectedVariableTypeException;
use axis\exceptions\UnresolvedClassException;
use axis\specification\di\DependencyDefinition as DependencyDefinitionInterface;
use ReflectionClass;
use ReflectionMethod;

class DI implements \axis\specification\di\DI
{
    /**
     * @var DependencyDefinitionInterface[]
     */
    private $_definitions = [];
    private $_singletons;

    /**
     * @param string $contract
     * @param string $agent
     * @return DependencyDefinition
     */
    public function set(string $contract, string $agent)
    {
        $definition = new DependencyDefinition($agent);
        $this->_definitions[$contract] = $definition;
        return $definition;
    }

    /**
     * @param string $contract
     * @param callable|null $beforeInstantiate
     * @return mixed
     */
    public function get(string $contract, callable $beforeInstantiate = null)
    {
        if (!$this->has($contract)) {
            throw new \InvalidArgumentException('Passed contract was not registered to create it: ' . $contract);
        }
        $definition = $this->getDefinition($contract);
        if ($definition->isSingleton()) {
            if (!$this->hasInstantiatedSingleton($contract)) {
                $agentInstance = $this->resolve($definition);
                $this->_singletons[$contract] = $agentInstance;
            }
            return $this->_singletons[$contract];
        } else {
            return $this->resolve($definition);
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
    public function getSingleton(string $contract, callable $beforeInstantiate = null)
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
     * @return mixed
     * @throws CannotInstantiateException
     * @throws UnexpectedVariableTypeException
     * @throws UnresolvedClassException
     */
    public function resolve($agentOrDefinition)
    {
        if (is_string($agentOrDefinition)) {

        } else if ($agentOrDefinition instanceof DependencyDefinitionInterface) {
            $classInspector = new ReflectionClass($agentOrDefinition->getAgent());
            $constructorInspector = $classInspector->getConstructor();
            if ($constructorInspector) {
                if ($agentOrDefinition->isArgumentsMap()) {
                    $arguments = $this->resolveConstructorMap($constructorInspector, $agentOrDefinition);
                } else {
                    $arguments = $this->resolveConstructorList($constructorInspector, $agentOrDefinition);
                }
                $agentInstance = $classInspector->newInstanceArgs($arguments);
            } else {
                $agentInstance = $classInspector->newInstance();
            }
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
    private function resolveConstructorList(ReflectionMethod $constructorInspector, DependencyDefinitionInterface $definition)
    {
        $expectedArguments = $constructorInspector->getParameters();
        if (count($definition->getArguments()) < count($expectedArguments)) {
            $arguments = $definition->getArguments();
            $missingArguments = array_slice($expectedArguments, count($definition->getArguments()));
            foreach ($missingArguments as $argument) {
                /* @var \ReflectionParameter $argument */
                $argumentClass = $argument->getClass();
                if (!$argumentClass) {
                    if (!$argument->isOptional()) {
                        throw new CannotInstantiateException($definition->getAgent(), 'is not enough arguments passed');
                    } else {
                        $arguments[] = null;
                    }
                } else {
                    if ($this->has($argumentClass->name)) {
                        $dependency = $this->get($argumentClass->name);
                        $arguments[] = $dependency;
                    } elseif (!$argument->isOptional()) {
                        throw new UnresolvedClassException($argumentClass->name);
                    } else {
                        $arguments[] = null;
                    }
                }
            }
            return $arguments;
        }
        return null;
    }

    private function resolveConstructorMap()
    {

    }
}