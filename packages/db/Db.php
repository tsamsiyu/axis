<?php namespace axis\db;

use axis\specification\db\ConnectorInterface;
use axis\specification\db\DbInterface;
use axis\specification\db\SchemaInterface;
use PDOStatement;
use PDO;

class Db implements DbInterface
{
    private $_connector;
    private $_schema;
    private $_pdo;

    public function __construct(ConnectorInterface $connector, SchemaInterface $schema)
    {
        $this->_connector = $connector;
        $this->_schema = $schema;
    }

    public function getSchema() : SchemaInterface
    {
        return $this->_schema;
    }

    public function getConnector() : ConnectorInterface
    {
        return $this->_connector;
    }

    public function getPdo() : PDO
    {
        if (!isset($this->_pdo)) {
            $this->_pdo = $this->_connector->connect();
        }
        return $this->_pdo;
    }

    public function query(string $sql, array $values = []) : PDOStatement
    {
        $statement = $this->prepare($sql, $values);
        $statement->execute();
        return $statement;
    }

    public function prepare(string $sql, array $values = []) : PDOStatement
    {
        $statement = $this->getPdo()->prepare($sql, $values);
        return $statement;
    }
}