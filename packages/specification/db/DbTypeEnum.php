<?php namespace axis\specification\db;

interface DbTypeEnum
{
    const INTEGER = 1;
    const FLOAT = 2;
    const DATE = 3;
    const DATETIME = 4;
    const YEAR = 5;
    const TIME = 6;
    const STRING = 7;
    const ENUM = 8;
    const TIMESTAMP = 9;
    const BLOB = 10;
    const DECIMAL = 11;
    const COMPLEX = 12;
    const BIT = 13;
    const BOOL = 14;
    const SET = 15;

    public function castValue($value);
}