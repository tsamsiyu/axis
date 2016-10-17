<?php namespace axis\db;

use axis\specification\db\ConnectorInterface;
use axis\specification\db\DsnInterface;
use PDO;

class Connector implements ConnectorInterface
{
    private $_pdo;

    public function __construct(DsnInterface $dsn, string $username, string $password)
    {
        if ($dsn instanceof DsnInterface) {
            $dsn = $dsn->build();
        }

        $this->_pdo = new PDO($dsn, $username, $password);
    }

    public function connect() : PDO
    {

    }
}