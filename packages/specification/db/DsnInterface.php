<?php namespace axis\specification\db;

interface DsnInterface
{
    public function getPort() : int;

    public function getHost() : string;

    public function getDriverName() : string;

    public function getDbName() : string;

    public function getCharset() : string;

    public function build() : string;
}