<?php namespace axis\specification\db;

interface CommandInterface
{
    public function __construct(ConnectorInterface $connector);
}