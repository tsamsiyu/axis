<?php namespace axis\db\mysql;

use axis\db\AbstractSchema;
use axis\specification\db\TableSchemaInterface;

class Schema extends AbstractSchema
{
    public function createTableSchema(string $tableName) : TableSchemaInterface
    {
        return new TableSchema($tableName, $this->getDb());
    }

    public function quoteTableName(string $name) : string
    {
        return strpos($name, '`') !== false ? $name : "`$name`";
    }
}