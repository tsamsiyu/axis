<?php namespace axis\specification\db;

interface SchemaInterface
{
    public function __construct(ConnectorInterface $connector);
}