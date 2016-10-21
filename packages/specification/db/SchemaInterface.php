<?php namespace axis\specification\db;

interface SchemaInterface
{
    public function getTableSchema(string $tableName) : TableSchemaInterface;

    public function quoteTableName(string $tableName) : string;
}