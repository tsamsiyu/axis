<?php namespace axis\specification\db;

interface DbInterface
{
    public function getConnector() : ConnectorInterface;

    public function newCommand(string $sql = null, array $params = []) : CommandInterface;

    public function getSchema() : SchemaInterface;
}