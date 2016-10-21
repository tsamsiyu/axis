<?php namespace axis\db;

use axis\specification\db\DsnInterface;

class Dsn implements DsnInterface
{
    private $_port = 80;
    private $_host;
    private $_dbname;
    private $_driverName;
    private $_charset = 'utf8';

    public function __construct(string $driverName, string $host, string $dbname)
    {
        $this->setDriverName($driverName)
            ->setHost($host)
            ->setDbname($dbname);
    }

    public function getHost() : string
    {
        return $this->_host;
    }

    public function setHost(string $host) : self
    {
        $this->_host = $host;
        return $this;
    }

    public function setPort(int $port) : self
    {
        $this->_port = $port;
        return $this;
    }

    public function getPort() : int
    {
        return $this->_port;
    }

    public function getDbname() : string
    {
        return $this->_dbname;
    }

    public function setDbname(string $dbname) : self
    {
        $this->_dbname = $dbname;
        return $this;
    }

    public function getDriverName() : string
    {
        return $this->_driverName;
    }

    public function setDriverName(string $driverName) : self
    {
        $this->_driverName = $driverName;
        return $this;
    }

    public function setCharset(string $charset) : self
    {
        $this->_charset = $charset;
        return $this;
    }

    public function getCharset() : string
    {
        return $this->_charset;
    }

    public function build() : string
    {
        $dsn = $this->getDriverName() . ':';
        $dsn .= 'host=' . $this->getHost() . ';';
        $dsn .= 'port=' . $this->getPort() . ';';
        $dsn .= 'dbname=' . $this->getDbname() . ';';
        $dsn .= 'charset=' . $this->getCharset() . ';';
        return $dsn;
    }
}