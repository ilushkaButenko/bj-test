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
}
