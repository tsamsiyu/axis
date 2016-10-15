<?php namespace axis\tests\mocks\car;

class BMWCar implements CarInterface
{
    public $year;
    public $engine;

    public function __construct($capacity, $valves, EngineInterface $engine)
    {
        $this->engine = $engine;
    }
}