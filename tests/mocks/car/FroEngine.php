<?php namespace axis\tests\mocks\car;

class FroEngine implements EngineInterface
{
    public function __construct(MotorOilInterface $motorOil)
    {
    }
}