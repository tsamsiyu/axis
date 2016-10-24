<?php namespace axis\db;

use axis\specification\db\SchemaInterface;
use axis\specification\db\TableSchemaInterface;

abstract class AbstractSchema implements SchemaInterface
{
    private $_tables;

    public function getTableSchema(string $tableName) : TableSchemaInterface
    {
        if (!isset($this->_tables[$tableName])) {
            $this->_tables[$tableName] = $this->injectTableSchema($tableName);
        }
        return $this->_tables[$tableName];
    }

    public function quoteTableName(string $tableName) : string
    {
        // TODO: Implement quoteTableName() method.
    }

    abstract public function quoteSign() : string;
}