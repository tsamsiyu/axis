<?php namespace axis\db\dbs;

use axis\db\commands\PostgreSqlCommand;
use axis\db\Db;
use axis\db\schemas\PostgreSqlSchema;

class PostgreSqlDb extends Db
{
    protected $schemaClass = PostgreSqlSchema::class;
    protected $commandClass = PostgreSqlCommand::class;
}