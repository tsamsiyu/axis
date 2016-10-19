<?php namespace axis\db;

use axis\specification\db\ConnectorInterface;
use axis\specification\db\DsnInterface;
use PDO;

class Connector implements ConnectorInterface
{
    private $_dsn;
    private $_username;
    private $_password;
    private $_pdo;

    public function __construct(DsnInterface $dsn, string $username, string $password)
    {
        if ($dsn instanceof DsnInterface) {
            $dsn = $dsn->build();
        }
        $this->_dsn = $dsn;
        $this->_username = $username;
        $this->_password = $password;
    }

    public function getConnection() : PDO
    {
        if (!isset($this->_pdo)) {
            $this->_pdo = new PDO($this->_dsn, $this->_username, $this->_password);
        }
        return $this->_pdo;
    }
}