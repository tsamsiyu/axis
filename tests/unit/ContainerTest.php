<?php

use axis\exceptions\CannotInstantiateException;
use axis\exceptions\UnresolvedClassException;
use axis\tests\mocks\car\CastrolMotorOil;
use axis\tests\mocks\car\EngineInterface;
use axis\tests\mocks\car\FroEngine;
use axis\tests\mocks\car\CarInterface;
use axis\tests\mocks\car\BMWCar;
use axis\tests\mocks\car\MotorOilInterface;

class ContainerTest extends \Codeception\Test\Unit
{
    use Codeception\Specify;

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
//        $this->specify('must resolve dependencies and dependencies of dependencies', function () {
//            $container = new Container();
//            $container->set(CarInterface::class, BMWCar::class);
//            $this->tester->expectException(new UnresolvedClassException(EngineInterface::class), function () use ($container) {
//                $container->get(CarInterface::class, [8, 11]);
//            });
//            $container->set(EngineInterface::class, FroEngine::class);
//            $this->tester->expectException(new UnresolvedClassException(MotorOilInterface::class), function () use ($container) {
//                $container->get(CarInterface::class, [8, 11]);
//            });
//            $container->set(MotorOilInterface::class, CastrolMotorOil::class);
//            $car = $container->get(CarInterface::class, [8, 11]);
//            expect($car)->isInstanceOf(BMWCar::class);
//            $this->tester->expectException(new CannotInstantiateException(BMWCar::class, 'is not enough arguments passed'), function () use ($container) {
//                $container->get(CarInterface::class);
//            });
//        });
//
//        $this->specify('must create singletons', function () {
//            $container = new Container();
//            $container->setSingleton(EngineInterface::class, FroEngine::class);
//            $container->set(MotorOilInterface::class, CastrolMotorOil::class);
//            $engine1 = $container->get(EngineInterface::class);
//            $engine2 = $container->get(EngineInterface::class);
//            $engine3 = $container->getSingleton(EngineInterface::class);
//            $oil1 = $container->get(MotorOilInterface::class);
//            $oil2 = $container->get(MotorOilInterface::class);
//            expect($engine2 === $engine1)->true();
//            expect($engine2 === $engine3)->true();
//            expect($oil1 !== $oil2)->true();
//        });
//
//        $this->specify('must configure container objects', function () {
//            $container = new Container;
//            $container->set(MotorOilInterface::class, CastrolMotorOil::class);
//        });
    }
}