<?php namespace axis\specification\store;

interface StoreSchemaInterface
{
    /**
     * @return string[]
     */
    public function getAvailableEntities() : array;
}