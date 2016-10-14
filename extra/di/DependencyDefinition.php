<?php namespace axis\di;

class DependencyDefinition implements \axis\specification\di\DependencyDefinition
{
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
     * @return \axis\specification\di\DependencyDefinition
     */
    public function constructorMap(array $map)
    {
        $this->_constructorMap = $map;
        return $this;
    }

    /**
     * @param array $list
     * @return \axis\specification\di\DependencyDefinition
     */
    public function constructorList(array $list)
    {
        $this->_constructorList = $list;
        return $this;
    }

    /**
     * @return \axis\specification\di\DependencyDefinition
     */
    public function singleton()
    {
        $this->_scope = self::SCOPE_SINGLETON;
        return $this;
    }

    /**
     * @return \axis\specification\di\DependencyDefinition
     */
    public function prototype()
    {
        $this->_scope = self::SCOPE_PROTOTYPE;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isSingleton()
    {
        return $this->_scope === self::SCOPE_SINGLETON;
    }

    /**
     * @return mixed
     */
    public function isPrototype()
    {
        return $this->_scope === self::SCOPE_PROTOTYPE;
    }

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