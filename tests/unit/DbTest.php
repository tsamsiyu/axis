<?php

use axis\db\commands\MysqlCommand;
use axis\db\connectors\MySqlConnector;
use axis\db\Db;
use axis\db\dbs\MySqlDb;
use axis\db\Dsn;
use axis\db\schemas\MySqlSchema;
use axis\di\DependencyInjector;
use axis\IoC\ServiceLocator;
use Codeception\Test\Unit;

class DbTest extends Unit
{
    use Codeception\Specify;

    public function testCreateConnection()
    {
        $di = new DependencyInjector();
        $services = new ServiceLocator($di);
        $services->set('mysql', function () {
            $dsn = new Dsn('mysql', 'localhost', 'axisTests');
            $connector = new MySqlConnector($dsn, 'tsamsiyu', '123');
            return new MySqlDb($connector);
        });
    }
}