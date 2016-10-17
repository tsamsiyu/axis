<?php namespace axis\db;

use axis\specification\db\ConnectionInterface;
use axis\specification\db\ConnectorInterface;

class Connection implements ConnectionInterface
{
    private $_pdo;

    public function __construct(ConnectorInterface $connector)
    {
        $this->_pdo = $connector->connect();
    }
}