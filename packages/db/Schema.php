<?php namespace axis\db;

use axis\specification\db\ConnectorInterface;
use axis\specification\db\SchemaInterface;

class Schema implements SchemaInterface
{

    public function __construct(ConnectorInterface $connector)
    {
    }
}