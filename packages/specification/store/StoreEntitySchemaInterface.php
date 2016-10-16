<?php namespace axis\specification\store;

interface StoreEntitySchemaInterface
{
    public function getPrimaryKey() : string;

    public function getProperties();
}