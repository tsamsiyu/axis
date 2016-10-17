<?php namespace axis\specification\db;

interface QueryInterface
{
    public function limit(int $limit) : self;

    public function offset(int $offset) : self;

    public function where($condition) : self;

    public function join(string $table, $condition, $type);

    /**
     * @return object
     */
    public function find();

    /**
     * @return object[]
     */
    public function findAll() : array;
}