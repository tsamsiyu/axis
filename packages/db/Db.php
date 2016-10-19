<?php namespace axis\db;

use axis\helpers\ObjectHelper;
use axis\specification\db\CommandInterface;
use axis\specification\db\ConnectorInterface;
use axis\specification\db\DbInterface;
use axis\specification\db\SchemaInterface;

class Db implements DbInterface
{
    private $_connector;
    private $_schema;

    protected $schemaClass;
    protected $commandClass;

    public function __construct(ConnectorInterface $connector, $schemaClass, $commandClass)
    {
        if (!ObjectHelper::isImplementationOf($schemaClass, SchemaInterface::class)) {
            throw new \InvalidArgumentException('Schema class must implement SchemaInterface');
        }
        if (!ObjectHelper::isImplementationOf($commandClass, CommandInterface::class)) {
            throw new \InvalidArgumentException('Command class must implement CommandInterface');
        }
        $this->_connector = $connector;
        $this->schemaClass = $schemaClass;
        $this->commandClass = $commandClass;
    }

    public function newCommand() : CommandInterface
    {
        return new $this->commandClass;
    }

    public function getSchema() : SchemaInterface
    {
        if (!isset($this->_schema)) {
            $this->_schema = new $this->schemaClass;
        }
        return $this->_schema;
    }

    public function getConnector() : ConnectorInterface
    {
        if (!isset($this->_connector)) {
            $this->_connector = new $this->_connector;
        }
        return $this->_connector;
    }
}