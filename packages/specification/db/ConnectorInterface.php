<?php namespace axis\specification\db;

use PDO;

interface ConnectorInterface
{
    public function connect() : PDO;
}