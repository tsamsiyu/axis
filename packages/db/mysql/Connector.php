<?php namespace axis\db\mysql;

use PDO;

class Connector extends \axis\db\Connector
{
    public function afterConnect(PDO $pdo)
    {
        parent::afterConnect($pdo);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
}