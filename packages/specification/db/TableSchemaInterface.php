<?php namespace axis\specification\db;

interface TableSchemaInterface
{
    public function getPrimaryKeys() : string;

    /**
     * @return TableSchemaInterface[]
     */
    public function getColumns() : array;

    public function getColumn(string $name) : TableColumnInterface;

    public function hasColumn(string $name) : bool;

    public function isPrimaryKey(string $name) : bool;
}