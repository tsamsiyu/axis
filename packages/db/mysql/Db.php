<?php namespace axis\db\mysql;

use axis\db\AbstractDb;
use axis\db\Command;
use axis\specification\db\CommandInterface;
use axis\specification\db\SchemaInterface;

class Db extends AbstractDb
{
    function createSchema() : SchemaInterface
    {
        return new Schema($this);
    }
}