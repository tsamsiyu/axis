<?php namespace axis\specification\store;

interface StoreInterface
{
    public function __construct(StoreConnectionInterface $connection);
}