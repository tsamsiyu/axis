<?php namespace axis\helpers;

class PhpTypeEnum extends PhpScalarTypeEnum
{
    const ARRAY = 5;
    const OBJECT = 6;
    const RESOURCE = 7;

    public function castValue($value)
    {
        if ($this->getValue() === self::ARRAY) {
            return (array)$value;
        } else if ($this->getValue() === self::OBJECT) {
            return (object)$value;
        } else if ($this->getValue() === self::RESOURCE) {
            throw new \Exception('Cannot cast value to resource');
        } else {
            return parent::castValue($value);
        }
    }
}