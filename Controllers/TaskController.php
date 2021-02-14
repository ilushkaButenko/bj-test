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

        $validator = Validator::init($_POST['name'], 'name')
            ->isString()
            ->isMatch('/[A-Z][a-z]+(\s[A-Z][a-z]+)?/', 'Invalid name');
        
        $validator->newValidation($_POST['email'], 'email')
            ->isEmail();

        $validator->newValidation($_POST['task'], 'task')
            ->isNotEmptyString();

        if ($validator->hasErrors()) {
            return View::render('task-create', [
                'oldInput' => $_POST,
                'errors' => $validator->getErrors()
            ]);
        }

        $newTask = new Task([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'task' => $_POST['task']
        ]);
        $newTask->save();

        header('Location: ' . BASE_URI . 'task/list');
    }
}
