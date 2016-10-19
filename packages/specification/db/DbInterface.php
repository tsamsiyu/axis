<?php namespace axis\specification\db;

interface DbInterface
{
    public function getConnector() : ConnectorInterface;

    public function newCommand() : CommandInterface;

    public function getSchema() : SchemaInterface;
}