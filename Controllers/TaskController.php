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

        // Redirect to /task if need 1st page
        if ($requestedPageNumber == 1 && App::getInstance()->getRouter()->getClearUri() !== 'task') {
            App::getInstance()->redirect('task');
        }

        $this->getOrderSettingsFromRequest();

        $orderBy = isset($_SESSION['orderBy']) ? $_SESSION['orderBy'] : 'id';
        $orderDirection = isset($_SESSION['orderDirection']) ? $_SESSION['orderDirection'] : 'desc';

        View::render('task-list', [
            'tasks' => Task::getTaskListPaginate($tasksPerPage, $requestedPageNumber, $orderBy, $orderDirection),
            'pageCount' => $pageCount,
            'currentPage' => $requestedPageNumber,
            'tasksPerPage' => $tasksPerPage,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
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

    public function delete()
    {
        if (!$_SESSION['auth']) {
            App::getInstance()->setStatusForbidden();
            return View::render('notauthorised');
        }
        
        // Check uri argument
        $validator = Validator::init($this->arg)
            ->isString()
            ->isNumber();

        // If bad arg
        if ($validator->hasErrors()) {
            App::getInstance()->setStatusNotFound();
            return View::render('notfound');
        }

        // Database delete query
        $result = Task::delete($this->arg);

        // Success deleted
        if ($result) {
            return App::getInstance()->redirect('task');
        }

        // An error was during query
        App::getInstance()->setStatusNotFound();
        return View::render('notfound');
    }

    public function done()
    {
        if (!$_SESSION['auth']) {
            App::getInstance()->setStatusForbidden();
            return View::render('notauthorised');
        }
        
        // Check uri argument
        $validator = Validator::init($this->arg)
            ->isString()
            ->isNumber();

        // If bad arg
        if ($validator->hasErrors()) {
            App::getInstance()->setStatusNotFound();
            return View::render('notfound');
        }

        $task = new Task([
            'id' => $this->arg,
            'done' => 1
        ]);
        $result = $task->save();

        // Success deleted
        if ($result) {
            return App::getInstance()->redirect('task');
        }

        // An error was during query
        App::getInstance()->setStatusNotFound();
        return View::render('notfound');
    }

    private function getOrderSettingsFromRequest()
    {
        if (isset($_POST['orderBy'])) {
            $validator = Validator::init($_POST['orderBy'], 'orderBy')
                ->isString()
                ->isMatch('/[a-zA-Z]+/');

            $sortArgumentsErrors = $validator->getErrors();

            $_SESSION['orderBy'] = $sortArgumentsErrors['orderBy'] === false ? $_POST['orderBy'] : 'id';
        }

        if (isset($_POST['orderDirection'])) {
            $validator = Validator::init(strtolower($_POST['orderDirection']), 'orderDirection')
                ->isString()
                ->isMatch('/(asc)|(desc)/');
            $sortArgumentsErrors = $validator->getErrors();

            $_SESSION['orderDirection'] = $sortArgumentsErrors['orderDirection'] === false ? $_POST['orderDirection'] : 'DESC';
        }
    }
}
