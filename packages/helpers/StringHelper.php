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

    /**
     * @param string $entity
     * @param string $delimiter
     * @return \Generator
     */
    public static function cutByPiece(string $entity, $delimiter = '.')
    {
        yield $entity;
        while (($pos = strrpos($entity, $delimiter)) !== false) {
            $entity = substr($entity, 0, $pos);
            yield $entity;
        }
    }
}