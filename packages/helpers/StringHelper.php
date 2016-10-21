<?php namespace axis\helpers;

class StringHelper
{
    public static function contains(string $entity, $sub)
    {
        $sub = (array)$sub;
        foreach ($sub as $item) {
            if (strpos($entity, $item) === false) {
                return false;
            }
        }
        return true;
    }

    public static function containsOne(string $entity, $sub)
    {
        $sub = (array)$sub;
        foreach ($sub as $item) {
            if (strpos($entity, $item) !== false) {
                return true;
            }
        }
        return false;
    }
}