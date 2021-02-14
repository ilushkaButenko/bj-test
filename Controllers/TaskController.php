<?php

namespace iButenko\Controllers;

use iButenko\App\Controller;
use iButenko\App\View;
use iButenko\Models\Task;
use iButenko\App\Validator;

/**
 * TaskController
 */
class TaskController extends Controller
{
    public function list()
    {
        View::render('task-list');
    }

    public function create()
    {
        if (empty($_POST)) {
            return View::render('task-create');
        }
    }

    public function store()
    {
        // Validation
        // Accept args
        $validationErrors = [];

        $nameValidatorResult = Validator::init($_POST['name'])
            ->isString()
            ->isMatch('/[A-Z][a-z]+(\s[A-Z][a-z]+)?/', 'Invalid name')
            ->error();
        if ($nameValidatorResult !== false) {
            $validationErrors[] = $nameValidatorResult;
        }

        if (isset($validationErrors)) {
            return View::render('task-create', [
                'oldInput' => $_POST,
                'errors' => $validationErrors
            ]);
        }

        $newTask = new Task([
            'name' => 'test',
            'email' => 'YEAH',
            'task' => 'BABY'
        ]);
        $newTask->save();

        header('Location: ' . BASE_URI . 'task/list');
    }
}
