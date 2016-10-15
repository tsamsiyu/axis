<?php namespace axis\di;

use \axis\events\EventEmitterTrait;
use axis\specification\di\DependencyDefinitionInterface;

class DependencyDefinition implements DependencyDefinitionInterface
{
    use EventEmitterTrait;

    private $_scope = self::SCOPE_PROTOTYPE;
    private $_agent;
    private $_constructorList = [];
    private $_constructorMap;

    public function __construct($agent)
    {
        $this->_agent = $agent;
    }

    /**
     * @param array $map
     * @return $this
     */
    public function constructorMap(array $map)
    {
        $this->_constructorMap = $map;
        return $this;
    }

    /**
     * @param array $list
     * @return $this
     */
    public function constructorList(array $list)
    {
        $this->_constructorList = $list;
        return $this;
    }

    /**
     * @return $this
     */
    public function singleton()
    {
        $this->_scope = self::SCOPE_SINGLETON;
        return $this;
    }

    /**
     * @return $this
     */
    public function prototype()
    {
        $this->_scope = self::SCOPE_PROTOTYPE;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSingleton()
    {
        return $this->_scope === self::SCOPE_SINGLETON;
    }

    /**
     * @return bool
     */
    public function isPrototype()
    {
        return $this->_scope === self::SCOPE_PROTOTYPE;
    }

    /**
     * @return string
     */
    public function getAgent()
    {
        return $this->_agent;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        if ($this->isArgumentsMap()) {
            return $this->_constructorMap;
        } else {
            return $this->_constructorList;
        }
    }

    /**
     * @return bool
     */
    public function isArgumentsMap()
    {
        return (bool)$this->_constructorMap;
    }
}