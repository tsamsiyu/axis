<?php namespace tests\unit;

use axis\db\mysql\Connector;
use axis\db\mysql\Db;
use axis\db\mysql\Schema;
use axis\db\mysql\TableColumn;
use axis\db\mysql\TableSchema;
use axis\di\DependencyInjector;
use axis\exceptions\UnresolvedClassException;
use axis\IoC\ServiceLocator;
use axis\specification\db\ConnectorInterface;
use axis\specification\db\DbInterface;
use axis\specification\db\SchemaInterface;
use axis\specification\db\TableColumnInterface;
use axis\specification\db\TableSchemaInterface;
use axis\specification\di\DependencyDefinitionInterface;
use axis\specification\di\DependencyInjectorInterface;
use axis\specification\events\EventInterface;
use axis\tests\mocks\car\CastrolMotorOil;
use axis\tests\mocks\car\EngineInterface;
use axis\tests\mocks\car\FroEngine;
use axis\tests\mocks\car\CarInterface;
use axis\tests\mocks\car\BMWCar;
use axis\tests\mocks\car\MotorOilInterface;

class DependencyInjectorTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testCommunication()
    {
        $this->specify('must resolve dependencies and dependencies of dependencies', function () {
            $container = new DependencyInjector();
            $container->set(CarInterface::class, BMWCar::class)
                ->constructorList([2.4, 16])
                ->onEvent(DependencyDefinitionInterface::EVENT_BEFORE_CREATE, function (EventInterface $event) {
                    expect($event->getContext())->isInstanceOf(DependencyDefinitionInterface::class);
                });
            $this->tester->expectException(new UnresolvedClassException(EngineInterface::class), function () use ($container) {
                $container->get(CarInterface::class);
            });
            $container->set(EngineInterface::class, FroEngine::class);
            $this->tester->expectException(new UnresolvedClassException(MotorOilInterface::class), function () use ($container) {
                $container->get(CarInterface::class);
            });
            $container->set(MotorOilInterface::class, CastrolMotorOil::class);
            $car = $container->get(CarInterface::class);
            expect($car)->isInstanceOf(BMWCar::class);
        });

        $this->specify('must handle dependencies scope', function () {
            $container = new DependencyInjector();
            $container->set(CarInterface::class, BMWCar::class)->constructorMap([
                'capacity' => 2.4,
                'valves' => 16
            ]);
            $container->set(EngineInterface::class, FroEngine::class)->singleton();
            $container->set(MotorOilInterface::class, CastrolMotorOil::class);
            $engine1 = $container->get(EngineInterface::class);
            $engine2 = $container->get(EngineInterface::class);
            $engine3 = $container->getSingleton(EngineInterface::class);
            $oil1 = $container->get(MotorOilInterface::class);
            $oil2 = $container->get(MotorOilInterface::class);
            $car = $container->get(CarInterface::class);
            expect($engine2 === $engine1)->true();
            expect($engine2 === $engine3)->true();
            expect($oil1 !== $oil2)->true();
            expect($car->engine === $engine1)->true();
        });
    }

    public function testScope()
    {
        $container = new DependencyInjector();
        $container->set(DbInterface::class, Db::class);
        $container->scope('app:mysql',  function (DependencyInjectorInterface $mysqlContainer) {
            $mysqlContainer->set(ConnectorInterface::class, Connector::class);
            $mysqlContainer->set(SchemaInterface::class, Schema::class);
            $mysqlContainer->set(TableSchemaInterface::class, TableSchema::class);
            $mysqlContainer->set(TableColumnInterface::class, TableColumn::class);
        });

////        TODO: think about it
////        $container->set('DbInterface', 'Db')->scope('app:mysql');
//
//        $container->scope('app:mysql', function (DependencyInjectorInterface $mysqlContainer) {
//            return $mysqlContainer->get(DbInterface::class); // create Db using mysql scope
//        });
//
//        $container->scope('app:mysql')->get(DbInterface::class);
//
//        /** Service Locator */
//
//        $serviceLocator = new ServiceLocator($container);
//        $serviceLocator->set('db[app:mysql]', DbInterface::class);
//        $serviceLocator->set('db[app:mysql]', DbInterface::class); // error! service db is already exist
//        $serviceLocator->set('pgsql[app:mysql]', DbInterface::class);
//        $serviceLocator->get('db');
//        $serviceLocator->get('db:pgsql');
    }
}