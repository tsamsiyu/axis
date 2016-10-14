<?php namespace axis\tests\mocks\car;

class BMWCar implements CarInterface
{
    public $year;

    public function __construct($a, $b, EngineInterface $engine)
    {
    }
}