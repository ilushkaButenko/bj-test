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
        $pageCount = ($taskCount / $tasksPerPage) + (($taskCount % $tasksPerPage) ? 1 : 0);

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

        $filteredInput = static::filterHtmlInput($_POST);

        $validator = Validator::init($filteredInput['name'], 'name')
            ->isString()
            ->isMatch('/[A-Z][a-z]+(\s[A-Z][a-z]+)?/', 'Invalid name');
        
        $validator->newValidation($filteredInput['email'], 'email')
            ->isEmail();

        $validator->newValidation($filteredInput['task'], 'task')
            ->isNotEmptyString();

        if ($validator->hasErrors()) {
            return View::render('task-create', [
                'oldInput' => $filteredInput,
                'errors' => $validator->getErrors()
            ]);
        }

        $newTask = new Task([
            'name' => $filteredInput['name'],
            'email' => $filteredInput['email'],
            'task' => $filteredInput['task']
        ]);
        $newTask->save();

        header('Location: ' . BASE_URI . 'task');
    }
}
