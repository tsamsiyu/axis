<?php namespace axis\helpers;

use axis\types\Enum;

class PhpScalarTypeEnum extends Enum
{
    const INTEGER = 1;
    const FLOAT = 2;
    const STRING = 3;
    const BOOL = 4;

    public function castValue($value)
    {
        if ($this->getValue() === self::INTEGER) {
            return (int)$value;
        } else if ($this->getValue() === self::FLOAT) {
            return (float)$value;
        } else if ($this->getValue() === self::BOOL) {
            return (bool)$value;
        } else if ($this->getValue() === self::STRING) {
            return (string)$value;
        } else {
            throw new \Exception('Unattainable point');
        }
    }
}