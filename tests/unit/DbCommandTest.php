<?php namespace tests\unit;

use axis\db\Dsn;
use axis\db\mysql\Connector;
use axis\db\mysql\Db;
use axis\pdoQuery\PDOQuery;
use function axis\pdoQuery\sql;
use Codeception\Specify;
use Codeception\Test\Unit;

class DbCommandTest extends Unit
{
    use Specify;

    public function testFetchData()
    {
        // create mysql connection
        $dsn = new Dsn('mysql', 'localhost', 'axis_test');
        $connector = new Connector($dsn, 'root', 'q');
        $db = new Db($connector);

        // configure PDOQuery to work with mysql by default
        PDOQuery::registerPdo('mysql', $db->getPdo());
        PDOQuery::useRegisteredPdoAsDefault('mysql');

        sql('delete from users')->execute();
        $query = new PDOQuery($db->getPdo());
        $query->sql('insert into users(email) values("gloryicio17.a@yandex.ru")')->execute();
        $countUsers1 = (int)sql('select count(*) from users')->column();
        expect($countUsers1)->equals(1);
    }
}