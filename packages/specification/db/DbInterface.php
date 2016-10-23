<?php namespace axis\specification\db;

use PDO;
use PDOStatement;

interface DbInterface
{
    public function getConnector() : ConnectorInterface;

    public function getSchema() : SchemaInterface;

    public function getPdo() : PDO;

    public function query(string $sql, array $values = []) : PDOStatement;

    public function prepare(string $sql, array $values = []) : PDOStatement;
}