<?php namespace axis\db;

use axis\specification\db\CommandInterface;
use PDO;

class Command implements CommandInterface
{
    /**
     * @var PDO
     */
    private $_pdo;

    /**
     * @var \PDOStatement
     */
    private $_statement;

    public function __construct(PDO $pdo)
    {
        $this->_pdo = $pdo;
    }

    public function setSql(string $sql, array $values = []) : CommandInterface
    {
        $this->_statement = $this->_pdo->query($sql);
        $this->bindValues($values);
        return $this;
    }

    public function bindValues(array $values) : CommandInterface
    {
        if (!$this->_statement) {
            throw new \Exception('Please set sql before bind values');
        }
        foreach ($values as $name => $value) {
            if (is_integer($value)) {
                $this->_statement->bindValue($name, $value, PDO::PARAM_INT);
            } else if (is_bool($value)) {
                $this->_statement->bindValue($name, $value, PDO::PARAM_BOOL);
            } else if (is_null($value)) {
                $this->_statement->bindValue($name, $value, PDO::PARAM_NULL);
            } else if (is_string($value)) {
                $this->_statement->bindValue($name, $value, PDO::PARAM_STR);
            } else {
                $this->_statement->bindValue($name, $value);
            }
        }
        return $this;
    }

    public function fetch() : array
    {
        if (!$this->_statement) {
            throw new \Exception('Please set sql before fetch');
        }
        return $this->_statement->fetch();
    }

    public function fetchAll() : array
    {
        if (!$this->_statement) {
            throw new \Exception('Please set sql before fetch');
        }
        return $this->_statement->fetchAll();
    }

    public function fetchColumn() : array
    {
        if (!$this->_statement) {
            throw new \Exception('Please set sql before fetch');
        }
        return $this->_statement->fetchColumn();
    }
}