<?php namespace axis\specification\db;

interface QueryBuilderInterface
{
    public function build(QueryInterface $query) : string;
}