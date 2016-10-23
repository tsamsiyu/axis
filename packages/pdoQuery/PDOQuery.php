<?php namespace axis\pdoQuery;

use PDO;
use PDOStatement;
use Throwable;

class PDOQuery
{
    protected static $driversWithSavepoint = ['pgsql', 'mysql'];

    protected $sql;
    protected $values;
    protected $transactionLevel = 0;
    private $_pdo;

    private static $_pdoList = [];
    private static $_defaultPdo;

    public function __construct(PDO $pdo = null)
    {
        $this->_pdo = $pdo;
    }

    public function pdo($pdo) : self
    {
        if (is_string($pdo) && static::isRegisteredPdo($pdo)) {
            $this->_pdo = static::$_pdoList[$pdo];
        } else if ($pdo instanceof PDO) {
            $this->_pdo = $pdo;
        } else {
            throw new \Exception('Unrecognized pdo value passed');
        }
        return $this;
    }

    public function getPdo() : PDO
    {
        if (!$this->_pdo) {
            if (!static::$_defaultPdo) {
                throw new \Exception('Pdo was not specified');
            }
            $this->_pdo = static::$_pdoList[static::$_defaultPdo];
        }
        return $this->_pdo;
    }

    protected function canDoNestedTransaction() : bool
    {
        return in_array($this->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME), static::$driversWithSavepoint);
    }

    public static function registerPdo($name, PDO $pdo)
    {
        static::$_pdoList[$name] = $pdo;
    }

    public static function getRegisteredPdo($name)
    {
        return static::$_pdoList[$name];
    }

    public static function getRegisteredPdoList()
    {
        return static::$_pdoList;
    }

    public static function isRegisteredPdo($name)
    {
        return isset(static::$_pdoList[$name]);
    }

    public static function useRegisteredPdoAsDefault($name)
    {
        if (!static::isRegisteredPdo($name)) {
            throw new \InvalidArgumentException('That driver was not registered: ' . $name);
        }
        static::$_defaultPdo = $name;
    }

    public function sql(string $sql, array $values = []) : self
    {
        $this->sql = $sql;
        $this->values = $values;
        return $this;
    }

    protected function prepareAndExecute(callable $callback)
    {
        $statement = $this->getPdo()->prepare($this->sql, $this->values);
        $res = call_user_func($callback, $statement);
        return $res;
    }

    public function beginTransaction() {
        if($this->transactionLevel == 0 || !$this->canDoNestedTransaction()) {
            $this->getPdo()->beginTransaction();
        } else {
            $this->getPdo()->exec("SAVEPOINT LEVEL{$this->transactionLevel}");
        }
        $this->transactionLevel++;
    }

    public function commit() {
        $this->transactionLevel--;
        if($this->transactionLevel == 0 || !$this->canDoNestedTransaction()) {
            $this->getPdo()->commit();
        } else {
            $this->getPdo()->exec("RELEASE SAVEPOINT LEVEL{$this->transactionLevel}");
        }
        return $this;
    }

    public function rollBack() {
        $this->transactionLevel--;
        if($this->transactionLevel == 0 || !$this->canDoNestedTransaction()) {
            $this->getPdo()->rollBack();
        } else {
            $this->getPdo()->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->transactionLevel}");
        }
        return $this;
    }

    public function transaction(callable $callback)
    {
        try {
            $this->beginTransaction();
            call_user_func($callback, $this);
            $this->commit();
            return true;
        } catch (Throwable $e) {
            $this->rollBack();
            return false;
        }
    }

    public function execute()
    {
        return $this->prepareAndExecute(function (PDOStatement $statement) {
            return $statement->execute();
        });
    }

    public function record() : array
    {
        return $this->prepareAndExecute(function (PDOStatement $statement) {
            $statement->execute();
            return $statement->fetch();
        });
    }

    public function records() : array
    {
        return $this->prepareAndExecute(function (PDOStatement $statement) {
            $statement->execute();
            return $statement->fetchAll();
        });
    }

    public function column(int $index = 0)
    {
        return $this->prepareAndExecute(function (PDOStatement $statement) use ($index) {
            $statement->execute();
            return $statement->fetchColumn($index);
        });
    }

    public function columns(int $index = 0)
    {
        return $this->prepareAndExecute(function (PDOStatement $statement) use ($index) {
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_COLUMN, $index);
        });
    }

    public function count($table) : int
    {
        return (int)$this->sql('select count(*) from ' . $this->getPdo()->quote($table))->column();
    }
}

function sql(string $sql, array $values = []) : PDOQuery
{
    return (new PDOQuery())->sql($sql, $values);
}