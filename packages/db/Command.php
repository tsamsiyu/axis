<?php namespace axis\db;

use axis\specification\db\CommandInterface;
use axis\specification\db\ConnectorInterface;

class Command implements CommandInterface
{
    public function __construct(ConnectorInterface $connector)
    {
    }
}