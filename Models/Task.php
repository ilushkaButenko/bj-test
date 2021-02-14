<?php

namespace iButenko\Models;

use iButenko\App\Model;
use iButenko\App\App;

/**
 * Task model
 */
class Task extends Model
{
    protected $tableName = 'tasks';
    protected $columnNames = [
        'name',
        'email',
        'task',
        'done',
    ];

    public static function getTaskListPaginate($countPerPage, $pageNum = 1)
    {
        $pdo = App::getInstance()->getDatabase();

        $sql = 'SELECT (name, email, task) FROM tasks ORDER BY id DESC LIMIT ?, ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ($pageNum - 1) * $countPerPage,
            $countPerPage
        ]);
    }
}
