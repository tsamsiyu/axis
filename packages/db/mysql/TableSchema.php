<?php namespace axis\db\mysql;

use axis\specification\db\DbInterface;
use axis\specification\db\TableColumnInterface;
use axis\specification\db\TableSchemaInterface;

class TableSchema implements TableSchemaInterface
{
    private $_db;
    private $_tableName;
    private $_columns;
    private $_primaryKeys;

    public function __construct(string $tableName, DbInterface $db)
    {
        $this->_tableName = $tableName;
        $this->_db = $db;
    }

    public function getPrimaryKeys() : string
    {
        if (!isset($this->_primaryKeys)) {
            $this->initColumns();
        }
        return $this->_primaryKeys;
    }

    public function getColumns() : array
    {
        if (!isset($this->_columns)) {
            $this->initColumns();
        }
        return $this->_columns;
    }

    public function getColumn(string $name) : TableColumnInterface
    {
        return $this->getColumns()[$name];
    }

    public function hasColumn(string $name) : bool
    {
        return isset($this->getColumns()[$name]);
    }

    public function isPrimaryKey(string $name) : bool
    {
        return in_array($name, $this->getPrimaryKeys());
    }

    private function initColumns()
    {
        $this->_columns = [];
        $this->_primaryKeys = [];
        $tableName = $this->_db->getSchema()->quoteTableName($this->_tableName);
        $columns = $this->_db->query('SHOW COLUMNS FROM ' . $tableName)->fetchAll();
        foreach ($columns as $column) {
            $tableColumn = new TableColumn($column);
            $this->_columns[$tableColumn->getName()] = $tableColumn;
            if ($tableColumn->isPrimaryKey()) {
                $this->_primaryKeys[] = $tableColumn->getName();
            }
        }
    }
}