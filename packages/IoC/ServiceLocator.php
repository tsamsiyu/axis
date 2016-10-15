<?php namespace axis\IoC;

use axis\exceptions\UnexpectedVariableTypeException;
use axis\specification\di\DependencyInjectorInterface;
use axis\specification\IoC\ServiceLocatorInterface;
use axis\specification\object\Configurable;

class ServiceLocator implements ServiceLocatorInterface
{
    private $_definitions = [];
    private $_instances = [];
    private $_di;

    public function __construct(DependencyInjectorInterface $di)
    {
        $this->_di = $di;
    }

    public function get(string $name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException("Such service does not exist: `$name`");
        }
        if (!isset($this->_instances[$name])) {
            $this->_instances[$name] = $this->make($name);
        }
        return $this->_instances[$name];
    }

    public function set(string $name, $contract, array $configuration = [])
    {
        if (is_object($contract)) {
            $this->_definitions[$name] = [get_class($contract), $configuration];
            $this->_instances[$name] = $contract;
        } else if (is_callable($contract)) {
            $this->set($name, call_user_func($contract, $this, $this->_di), $configuration);
        } else if (is_string($contract)) {
            $this->_definitions[$name] = [$contract, $configuration];
        } else {
            throw new UnexpectedVariableTypeException($contract);
        }
    }

    public function make(string $name, array $configuration = [])
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException("Such service does not exist: `$name`");
        }
        list($contract, $commonConfiguration) = $this->_definitions[$name];
        $configuration = array_merge($commonConfiguration, $configuration);
        if ($this->_di->has($contract)) {
            $service = $this->_di->get($contract);
        } else {
            $service = $this->_di->resolve($contract);
        }
        $this->configureService($service, $configuration);
        return $service;
    }

    public function has(string $name)
    {
        return isset($this->_definitions[$name]);
    }

    public function configureService($service, $configuration)
    {
        if ($service instanceof Configurable) {
            $service->configureInstance($configuration);
        } else {
            foreach ($configuration as $name => $value) {
                $service->{$name} = $value;
            }
        }
    }

    public function configure(string $name, array $configuration)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException("Such service does not exist: `$name`");
        }
        $this->_definitions[$name][1] = array_merge($this->_definitions[$name][1], $configuration);
        if (isset($this->_instances[$name])) {
            $this->configureService($this->_instances[$name], $this->_definitions[$name][1]);
        }
    }
}