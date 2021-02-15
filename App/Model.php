<?php

namespace iButenko\App;

use Exception;
use iButenko\App\App;

/**
 * Main model class
 */
class Model
{
    // required
    protected static $tableName = '';

    // required
    protected static $columnNames = [];

    protected static $primaryKeyName = 'id';
    protected $primaryKeyValue = '';

    protected $values = [];

    /**
     * @param array $values field values
     */
    public function __construct($values)
    {
        foreach ($values as $columnName => $columnValue) {
            if (array_search($columnName, static::$columnNames) === false && $columnName !== static::$primaryKeyName) {
                throw new Exception('Column ' . $columnName . ' doesn\'t exist in table ' . static::$tableName);
            }
            if ($columnName === static::$primaryKeyName) {
                $this->primaryKeyValue = $columnValue;
            } else {
                $this->values[$columnName] = $columnValue;
            }
        }
    }

    public static function delete($primaryKeyValue)
    {
        $pdo = App::getInstance()->getDatabase();

        if (!static::hasRowWithKey($primaryKeyValue)) {
            return false;
        }

        $stmt = $pdo->prepare('DELETE FROM ' . static::$tableName . ' WHERE '
            . static::$primaryKeyName . ' = ?');
        $stmt->bindValue(1, $primaryKeyValue);
        return $stmt->execute();
    }
    
    /**
     * hasRowWithKey
     * 
     * Check if exists row with specified key value.
     *
     * @return boolean
     */
    public static function hasRowWithKey($primaryKeyValue)
    {
        $pdo = App::getInstance()->getDatabase();

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ' . static::$tableName . ' WHERE ' . static::$primaryKeyName . ' = ?');
        $stmt->bindValue(1, $primaryKeyValue);
        $stmt->execute();
        $count = $stmt->fetchColumn(0);

        return $count == 1 ? true : false;
    }

    public function save()
    {
        $pdo = App::getInstance()->getDatabase();
        
        if (!empty($this->primaryKeyValue)) {
            $sql = $this->buildUpdateQuery();
        } else {
            $sql = $this->buildInsertQuery();
        }

        $stmt = $pdo->prepare($sql);
        
        // Cast parameters
        foreach ($this->values as $columnName => $columnValue) {
            $stmt->bindParam(':' . $columnName, $this->values[$columnName]);
        }

        $result = $stmt->execute();

        // Throw exception if query did not pass
        if (isset($stmt->errorInfo()[2])) {
            throw new Exception($stmt->errorInfo()[2]);
        }

        return $result;
    }

    private function buildInsertQuery()
    {
        $sql = 'INSERT INTO ' . static::$tableName . ' (';

        // Add "`email`, `name`, ..." 
        foreach ($this->values as $columnName => $value) {
            $sql .= '' . $columnName . ', ';
        }

        // Add ") VALUES (" and delete last space
        $sql = rtrim($sql, ', ');
        $sql .= ') VALUES (';

        // Add inserting values ":name, :email, ..."
        foreach ($this->values as $columnName => $value) {
            $sql .= ':' . $columnName . ', ';
        }

        // Add ")" and delete last space
        $sql = rtrim($sql, ', ');
        $sql .= ')';

        return $sql;
    }

    private function buildUpdateQuery()
    {
        $sql = 'UPDATE ' . static::$tableName . ' SET';

        // Add " `email` = :email, `name` = :name,..." 
        foreach ($this->values as $columnName => $value) {
            $sql .= ' ' . $columnName . ' = :' . $columnName . ',';
        }

        // Delete last comma
        $sql = rtrim($sql, ',');

        // Add WHERE id = ...
        $sql .= ' WHERE ' . static::$primaryKeyName . ' = ' . $this->primaryKeyValue;

        return $sql;
    }

    public static function getRowCount()
    {
        $pdo = App::getInstance()->getDatabase();

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ' . static::$tableName);
        $stmt->execute();

        return $stmt->fetchColumn(0);
    }
}
