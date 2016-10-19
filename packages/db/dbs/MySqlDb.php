<?php namespace axis\db\dbs;

use axis\db\Db;
use axis\db\schemas\MySqlSchema;
use axis\db\commands\MysqlCommand;
use axis\specification\db\ConnectorInterface;

class MySqlDb extends Db
{
    public function __construct(ConnectorInterface $connector)
    {
        parent::__construct($connector, MySqlSchema::class, MysqlCommand::class);
    }
}