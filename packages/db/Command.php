<?php namespace axis\db;

use PDO;

class Command
{
//    /**
//     * @var PDO
//     */
//    private $_pdo;
//
//    private $_sql;
//
//    private $_bindingValues = [];
//
//    public function __construct(PDO $pdo)
//    {
//        $this->_pdo = $pdo;
//    }
//
//    public function setSql(string $sql, array $values = []) : CommandInterface
//    {
//        $this->_sql = $sql;
//        $this->bindValues($values);
//        return $this;
//    }
//
//    public function bindValues(array $values) : CommandInterface
//    {
//        $this->_bindingValues = array_merge($this->_bindingValues, $values);
//        return $this;
//    }
//
//    public function fetch() : array
//    {
//        $statement = $this->_pdo->prepare($this->_sql);
//        $statement->execute($this->_bindingValues);
//        return $statement->fetch();
//    }
//
//    public function fetchAll() : array
//    {
//        $statement = $this->_pdo->prepare($this->_sql);
//        $statement->execute($this->_bindingValues);
//        return $statement->fetchAll();
//    }
//
//    public function fetchColumn($index = 0) : string
//    {
//        $statement = $this->_pdo->prepare($this->_sql);
//        $statement->execute($this->_bindingValues);
//        return $statement->fetchColumn($index);
//    }
//
//    public function execute() : bool
//    {
//        $statement = $this->_pdo->prepare($this->_sql);
//        return $statement->execute($this->_bindingValues);
//    }
//
//    public function fetchColumnList($index = 0) : array
//    {
//        $statement = $this->_pdo->prepare($this->_sql);
//        $statement->execute($this->_bindingValues);
//        return $statement->fetchAll(PDO::FETCH_COLUMN, $index);
//    }
}