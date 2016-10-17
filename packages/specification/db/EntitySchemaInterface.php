<?php namespace axis\specification\db;

interface EntitySchemaInterface
{
    public function getPrimaryKey() : string;

    public function getProperties();
}