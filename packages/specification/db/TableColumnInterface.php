<?php namespace axis\specification\db;

interface TableColumnInterface
{
    public function isPrimaryKey() : bool;

    public function getType() : string;

    public function setType($value);

    public function getTypeSize();

    public function canBeNull() : bool;

    public function getDefaultValue() : string;

    public function getName() : string;

    public function isAutoIncrement() : string;
}