<?php namespace axis\specification\db;

interface CommandInterface
{
    public function setSql(string $sql, array $values = []) : self;

    public function bindValues(array $values) : self;

    public function fetch() : array;

    public function fetchAll() : array;

    public function fetchColumn() : array;
}