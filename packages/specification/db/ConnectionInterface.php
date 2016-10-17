<?php namespace axis\specification\db;

interface ConnectionInterface
{
    public function __construct(ConnectorInterface $connector);
}