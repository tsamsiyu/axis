<?php namespace axis\specification\store;

interface StoreEntityPropertyInterface
{
    public function getType() : string;

    public function getName() : string;

    public function isPrimaryKey() : bool;
}