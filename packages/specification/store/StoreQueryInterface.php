<?php namespace axis\specification\store;

interface StoreQueryInterface
{
    public function limit($limit) : self;

    public function offset($offset) : self;

    public function where($condition) : self;

    /**
     * @return object
     */
    public function find();

    /**
     * @return object[]
     */
    public function findAll() : array;
}