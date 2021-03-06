<?php

namespace tests\unit;

use axis\db\Dsn;
use axis\db\mysql\Connector;
use axis\db\mysql\Db;
use Codeception\Specify;
use Codeception\Test\Unit;

class DbTest extends Unit
{
    use Specify;

    public function testMySqlConnection()
    {
        $dsn = new Dsn('mysql', 'localhost', 'axis_test');
        $connector = new Connector($dsn, 'root', 'q');
        $db = new Db($connector);
        $usersTable = $db->getSchema()->getTableSchema('users');
        expect($usersTable->getColumns())->count(2);
        expect($usersTable->getColumn('id')->getTypeSize())->equals(11);
    }

    public function testBindValues()
    {
        $dsn = new Dsn('mysql', 'localhost', 'axis_test');
        $connector = new Connector($dsn, 'root', 'q');

        $db = new Db($connector);
        $db->query('delete from users')->execute();
        $db->query('insert into users(email) values("axisuser@gmail.com") ');
        $db->query('insert into users(email) values("axisuser2@gmail.com") ');

        $users = $db->query('select * from users')->fetchAll();

        expect($users)->count(2);
    }
}