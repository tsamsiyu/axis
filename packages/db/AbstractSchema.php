<?php namespace axis\db;

use axis\specification\db\DbInterface;
use axis\specification\db\SchemaInterface;
use axis\specification\db\TableSchemaInterface;

abstract class AbstractSchema implements SchemaInterface
{
    private $_tables;
    private $_db;

    public function __construct(DbInterface $db)
    {
        $this->_db = $db;
    }

    abstract public function createTableSchema(string $tableName) : TableSchemaInterface;

    public function getTableSchema(string $tableName) : TableSchemaInterface
    {
        if (!isset($this->_tables[$tableName])) {
            $this->_tables[$tableName] = $this->createTableSchema($tableName);
        }
        return $this->_tables[$tableName];
    }

    public function getDb() : DbInterface
    {
        return $this->_db;
    }
}