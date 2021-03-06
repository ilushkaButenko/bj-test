<?php

namespace iButenko\Models;

use iButenko\App\Model;
use iButenko\App\App;

/**
 * Task model
 */
class Task extends Model
{
    protected static $tableName = 'tasks';
    protected static $columnNames = [
        'name',
        'email',
        'task',
        'done',
        'updated'
    ];
    
    /**
     * getTaskListPaginate
     * 
     * Gets part of table content by dividing to pages.
     *
     * @param  mixed $countPerPage
     * @param  mixed $pageNum
     * @param  mixed $orderBy
     * @param  mixed $orderDirection
     * @return array table content
     */
    public static function getTaskListPaginate($countPerPage, $pageNum = 1, $orderBy = 'id', $orderDirection = 'DESC')
    {
        $pdo = App::getInstance()->getDatabase();

        $sql = 'SELECT * FROM tasks ORDER BY ' . $orderBy . ' ' . $orderDirection . ' LIMIT '
            . (($pageNum - 1) * $countPerPage) . ', ' . ($countPerPage);
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
