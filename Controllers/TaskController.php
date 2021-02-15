<?php

namespace iButenko\Controllers;

use iButenko\App\App;
use iButenko\App\Controller;
use iButenko\App\View;
use iButenko\Models\Task;
use iButenko\App\Validator;

/**
 * TaskController
 */
class TaskController extends Controller
{
    public function index()
    {
        $this->page();
    }

    public function page()
    {
        $requestedPageNumber = 1;
        $taskCount = Task::getRowCount();
        $tasksPerPage = 3;
        $pageCount = ($taskCount / 3) + 1;

        $validator = Validator::init($this->arg)
            ->isString()
            ->isNumber()
            ->isLessOrEqualThan($pageCount);

        if ($validator->hasNoErrors()) {
            $requestedPageNumber = $this->arg;
        }

        // For uri
        if ($requestedPageNumber == 1 && $_SERVER['REQUEST_URI'] !== '/task') {
            App::getInstance()->redirect('task');
        }

        View::render('task-list', [
            'tasks' => Task::getTaskListPaginate($tasksPerPage, $requestedPageNumber),
            'pageCount' => $pageCount,
            'currentPage' => $requestedPageNumber,
            'tasksPerPage' => $tasksPerPage
        ]);
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
