<?php namespace axis\db;

use axis\specification\db\CommandInterface;
use axis\specification\db\ConnectorInterface;
use axis\specification\db\DbInterface;
use axis\specification\db\SchemaInterface;

abstract class AbstractDb implements DbInterface
{
    private $_connector;
    private $_schema;

    public function __construct(ConnectorInterface $connector)
    {
        $this->_connector = $connector;
    }

    abstract function createCommand() : CommandInterface;

    abstract function createSchema() : SchemaInterface;

    public function newCommand(string $sql = null, array $params = []) : CommandInterface
    {
        $command = $this->createCommand();
        if ($sql) {
            $command->setSql($sql);
            if ($params) {
                $command->bindValues($params);
            }
        }
        return $command;
    }

    public function getSchema() : SchemaInterface
    {
        if (!isset($this->_schema)) {
            $this->_schema = $this->createSchema();
        }
        return $this->_schema;
    }

    public function getConnector() : ConnectorInterface
    {
        return $this->_connector;
    }
}