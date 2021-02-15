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
    ];

    public static function getTaskListPaginate($countPerPage, $pageNum = 1)
    {
        $pdo = App::getInstance()->getDatabase();

        $sql = 'SELECT * FROM tasks ORDER BY id DESC LIMIT '
            . (($pageNum - 1) * $countPerPage) . ', ' . ($countPerPage);
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
