<?php namespace axis\db\mysql;

use axis\helpers\StringHelper;
use axis\specification\db\DbTypeEnum;
use axis\specification\db\TableColumnInterface;

class TableColumn implements TableColumnInterface
{
    private $_data;
    private $_type;

    public function __construct(array $columnData)
    {
        $this->_data = $columnData;
        $this->_type = static::discoverType($columnData['Type']);
    }

    public function isPrimaryKey() : bool
    {
        return strpos($this->_data['Key'], 'PRI') !== false;
    }

    public function getType() : string
    {
        return $this->_type;
    }

    public function setType($value)
    {
        $this->_type = $value;
    }

    public function canBeNull() : bool
    {
        return $this->_data['Null'] === 'NO';
    }

    public function getDefaultValue() : string
    {
        return $this->_data['Default'];
    }

    public function getName() : string
    {
        return $this->_data['Field'];
    }

    public function isAutoIncrement() : string
    {
        return strpos($this->_data['Extra'], 'auto_increment') !== false;
    }

    public static function discoverType(string $type)
    {
        if ($type === 'tinyint(1)' || StringHelper::contains($type, 'bool')) {
            return DbTypeEnum::BOOL;
        } elseif ($type === 'timestamp') {
            return DbTypeEnum::TIMESTAMP;
        } else if (StringHelper::containsOne($type, ['double', 'float'])) {
            return DbTypeEnum::FLOAT;
        } else if (StringHelper::contains($type, 'enum')) {
            return DbTypeEnum::ENUM;
        } else if ($type === 'date') {
            return DbTypeEnum::DATE;
        } else if ($type === 'datetime') {
            return DbTypeEnum::DATETIME;
        } else if ($type === 'time') {
            return DbTypeEnum::TIME;
        } else if (StringHelper::contains($type, 'set')) {
            return DbTypeEnum::SET;
        } else if (StringHelper::contains($type, 'int')) {
            return DbTypeEnum::INTEGER;
        } else if (StringHelper::containsOne($type, ['char', 'text'])) {
            return DbTypeEnum::STRING;
        } else if ($type === 'year') {
            return DbTypeEnum::YEAR;
        } else if (StringHelper::contains($type, 'bit')) {
            return DbTypeEnum::BIT;
        } else if (StringHelper::contains($type, 'blob')) {
            return DbTypeEnum::BLOB;
        } else {
            return DbTypeEnum::COMPLEX;
        }
    }

    public function getTypeSize()
    {
        if (preg_match('/\((\d+)\)/', $this->_data['Type'], $matches)) {
            return $matches[1];
        }
        return null;
    }
}