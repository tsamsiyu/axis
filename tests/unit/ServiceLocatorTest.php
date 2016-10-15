<?php


use axis\di\DependencyInjector;
use axis\IoC\ServiceLocator;
use axis\tests\mocks\car\CastrolMotorOil;
use axis\tests\mocks\car\EngineInterface;
use axis\tests\mocks\car\FroEngine;
use axis\tests\mocks\car\MotorOilInterface;
use axis\tests\mocks\site\ConsoleLogger;
use axis\tests\mocks\site\EchoHelper;
use axis\tests\mocks\site\LoggerInterface;

class ServiceLocatorTest extends \Codeception\Test\Unit
{
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

    // tests
    public function testCommunication()
    {
        $di = new DependencyInjector();
        $serviceLocator = new ServiceLocator($di);
        $di->set(LoggerInterface::class, ConsoleLogger::class);
        $serviceLocator->set('logger', LoggerInterface::class, [
            'debug' => true
        ]);
        expect($serviceLocator->has('logger'))->true();
        $logger = $serviceLocator->get('logger');
        expect($logger)->isInstanceOf(ConsoleLogger::class);
        $serviceLocator->configure('logger', [
            'level' => 'warning'
        ]);
        expect($logger->level)->equals('warning');
        expect($logger->debug)->equals(true);
        $logger2 = $serviceLocator->get('logger');
        expect($logger === $logger2)->true();
        $logger3 = $serviceLocator->make('logger');
        expect($logger !== $logger3)->true();

        $di->set(EngineInterface::class, FroEngine::class);
        $di->set(MotorOilInterface::class, CastrolMotorOil::class);
        $serviceLocator->set('carEngine', EngineInterface::class);
        $carEngine = $serviceLocator->get('carEngine');
        expect($carEngine)->isInstanceOf(EngineInterface::class);
        $carEngine2 = $serviceLocator->make('carEngine');
        expect($carEngine !== $carEngine2)->true();
        $serviceLocator->set('echo', EchoHelper::class);
        $echo = $serviceLocator->get('echo');
        expect($echo->logger)->isInstanceOf(LoggerInterface::class);
    }
}