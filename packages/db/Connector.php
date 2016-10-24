<?php namespace axis\db;

use axis\specification\db\ConnectorInterface;
use axis\specification\db\DsnInterface;
use PDO;

class Connector implements ConnectorInterface
{
    private $_dsn;
    private $_username;
    private $_password;

    public function __construct(DsnInterface $dsn, string $username, string $password)
    {
        if ($dsn instanceof DsnInterface) {
            $dsn = $dsn->build();
        }
        $this->_dsn = $dsn;
        $this->_username = $username;
        $this->_password = $password;
    }

    public function connect() : PDO
    {
        $pdo = new PDO($this->_dsn, $this->_username, $this->_password);
        $this->afterConnect($pdo);
        return $pdo;
    }

    public function afterConnect(PDO $pdo)
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
}