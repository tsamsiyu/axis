<?php namespace axis\helpers;

class ObjectHelper
{
    public static function isImplementationOf($class, $interface)
    {
        return isset(class_implements($class)[$interface]);
    }
}