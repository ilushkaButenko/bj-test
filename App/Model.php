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
     * @param array $values field values [column-name => value, ...]
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
    
    /**
     * get
     * 
     * Creates model instance that represents real row in database table
     * selected by primary key value.
     *
     * @param  mixed $primaryKeyValue
     * @return static|boolean instance of child model or false if not found
     */
    public static function get($primaryKeyValue)
    {
        $pdo = App::getInstance()->getDatabase();

        $stmt = $pdo->prepare('SELECT * FROM ' . static::$tableName . ' WHERE ' . static::$primaryKeyName . ' = ?');
        $stmt->bindValue(1, $primaryKeyValue);
        $stmt->execute();
        $entity = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$entity) {
            return false;
        }

        $modelInstance = new static($entity);

        return $modelInstance;
    }
    
    /**
     * delete
     * 
     * Deletes entity by primary key.
     *
     * @param  mixed $primaryKeyValue
     * @return boolean success
     */
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
    
    /**
     * save
     * 
     * Creates new entity if primary key value is not specified.
     * Otherwise updates existing entity.
     *
     * @return boolean
     */
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
    
    /**
     * buildInsertQuery
     *
     * @return string query
     */
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
    
    /**
     * buildUpdateQuery
     *
     * @return string query
     */
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
    
    /**
     * getRowCount
     * 
     * Gets count of entities in the table.
     *
     * @return string entities count
     */
    public static function getRowCount()
    {
        $pdo = App::getInstance()->getDatabase();

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ' . static::$tableName);
        $stmt->execute();

        return $stmt->fetchColumn(0);
    }
    
    /**
     * getValues
     * 
     * Return field values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
    
    /**
     * getPrimaryKeyValue
     *
     * @return mixed
     */
    public function getPrimaryKeyValue()
    {
        return $this->primaryKeyValue;
    }

    /**
     * getListPaginate
     * 
     * Gets part of table content by dividing to pages.
     *
     * @param  mixed $countPerPage
     * @param  mixed $pageNum
     * @param  mixed $orderBy
     * @param  mixed $orderDirection
     * @return array table content
     */
    public static function getListPaginate($countPerPage, $pageNum = 1, $orderBy = 'id', $orderDirection = 'DESC')
    {
        $pdo = App::getInstance()->getDatabase();

        $sql = 'SELECT * FROM ' . static::$tableName . ' ORDER BY ' . $orderBy . ' ' . $orderDirection . ' LIMIT '
            . (($pageNum - 1) * $countPerPage) . ', ' . ($countPerPage);
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
