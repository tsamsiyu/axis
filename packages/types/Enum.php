<?php namespace axis\types;

use ReflectionClass;

class Enum
{
    static private $_reflection;
    static private $_constants;

    private $_value;

    public function __construct($value)
    {
        if (!static::isConstant($value)) {
            throw new \InvalidArgumentException("Such constant is absent: `$value`");
        }
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public static function isConstant($value)
    {
        return in_array($value, static::getConstants());
    }

    /**
     * @return array
     */
    public static function getConstants()
    {
        if (!isset(static::$_constants)) {
            static::$_constants = static::getReflection()->getConstants();
        }
        return static::$_constants;
    }

    /**
     * @return ReflectionClass
     */
    public static function getReflection()
    {
        if (!isset(static::$_reflection)) {
            static::$_reflection = new ReflectionClass(static::class);
        }
        return static::$_reflection;
    }
}