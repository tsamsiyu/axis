<?php namespace axis\di;

use axis\specification\di\DependencyDefinitionInterface as BaseDependencyDefinitionInterface;

interface DependencyDefinitionInterface extends BaseDependencyDefinitionInterface
{
    /**
     * @param array $map
     * @return array
     */
    public function constructorMap(array $map);

    /**
     * @param array $list
     * @return array
     */
    public function constructorList(array $list);

    /**
     * @return $this
     */
    public function singleton();

    /**
     * @return $this
     */
    public function prototype();
}