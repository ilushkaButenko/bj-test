<?php

namespace iButenko\Controllers;

use iButenko\App\App;
use iButenko\App\Auth;
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
        $this->getPerPageSettingsFromRequest();
        $tasksPerPage = $_SESSION['perPage'] ?? 3;
        $pageCount = intdiv($taskCount, $tasksPerPage) + (($taskCount % $tasksPerPage) ? 1 : 0);

        if (App::getInstance()->getRouter()->getClearUri() !== '') {
            $validator = Validator::init($this->arg)
                ->isString()
                ->isNumber()
                ->isLessOrEqualThan($pageCount);

            if ($validator->hasNoErrors()) {
                $requestedPageNumber = $this->arg;
                $_SESSION['tasksPageNumber'] = $requestedPageNumber;
            } else {
                App::getInstance()->redirect('tasks/' . $pageCount);
                return;
            }
        } else {
            $_SESSION['tasksPageNumber'] = 1;
        }

        // Redirect to /task if need 1st page
        if ($requestedPageNumber == 1 && App::getInstance()->getRouter()->getClearUri() === 'tasks/1') {
            App::getInstance()->redirect('/');
        }

        $this->getOrderSettingsFromRequest();

        $orderBy = isset($_SESSION['orderBy']) ? $_SESSION['orderBy'] : 'id';
        $orderDirection = isset($_SESSION['orderDirection']) ? $_SESSION['orderDirection'] : 'desc';

        View::render('task-list', [
            'tasks' => Task::getListPaginate($tasksPerPage, $requestedPageNumber, $orderBy, $orderDirection),
            'pageCount' => $pageCount,
            'currentPage' => $requestedPageNumber,
            'tasksPerPage' => $tasksPerPage,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
            'perPage' => $tasksPerPage,
        ]);
    }

    public function create()
    {
        if (empty($_POST)) {
            return View::render('task-create', [
                'tasksUrl' => static::getLastPageUrl(),
            ]);
        }

        $filteredInput = static::filterHtmlInput($_POST);
        $validator = $this->validateCreateFormValues($filteredInput);

        if ($validator->hasErrors()) {
            return View::render('task-create', [
                'oldInput' => $filteredInput,
                'errors' => $validator->getErrors(),
                'tasksUrl' => static::getLastPageUrl(),
            ]);
        }

        $newTask = new Task([
            'name' => $filteredInput['name'],
            'email' => $filteredInput['email'],
            'task' => $filteredInput['task']
        ]);
        $result = $newTask->save();

        if (!$result) {
            App::getInstance()->setStatusError();
            View::render('servererror', [
                'tasksUrl' => static::getLastPageUrl(),
            ]);
        }

        return View::render('task-edit-success', [
            'task' => $newTask->getValues(),
            'createMode' => true,
            'tasksUrl' => static::getLastPageUrl(),
        ]);
    }

    public function edit()
    {
        Auth::check();

        // Store edited task
        if (isset($_POST['editMode']) && isset($_SESSION['taskToEdit'])) {

            // Validate input
            $filteredInput = static::filterHtmlInput($_POST);
            $validator = $this->validateCreateFormValues($filteredInput);

            if ($validator->hasErrors()) {
                return View::render('task-create', [
                    'oldInput' => $filteredInput,
                    'errors' => $validator->getErrors(),
                    'editMode' => true,
                    'tasksUrl' => static::getLastPageUrl(),
                ]);
            }

            $oldTask = Task::get($_SESSION['taskToEdit']);

            // Update task
            $task = new Task([
                'id' => $_SESSION['taskToEdit'],
                'name' => $filteredInput['name'],
                'email' => $filteredInput['email'],
                'task' => $filteredInput['task'],
                // updated = 1 if task text has been updated, otherwise save old value
                'updated' => ($oldTask->getValues()['task'] != $filteredInput['task']) ? 1 : $oldTask->getValues()['updated']
            ]);
            $task->save();

            return View::render('task-edit-success', [
                'task' => $task->getValues(),
                'tasksUrl' => static::getLastPageUrl(),
            ]);
        }

        // Check uri argument
        $validator = Validator::init($this->arg)->isString()->isNumber();

        // If bad arg
        if ($validator->hasErrors()) {
            App::getInstance()->setStatusNotFound();
            return View::render('notfound');
        }

        $task = Task::get($this->arg);
        if (!$task) {
            App::getInstance()->setStatusNotFound();
            return View::render('notfound');
        }

        // Store id of task to edit
        $_SESSION['taskToEdit'] = $task->getPrimaryKeyValue();

        View::render('task-create', [
            'oldInput' => $task->getValues(),
            'editMode' => true,
            'tasksUrl' => static::getLastPageUrl(),
        ]);
    }
    
    /**
     * validateCreateFormValues
     * 
     * Validates inputs for task create form
     *
     * @param $values ['name' => 'abc', ...] All form fields that need valudation
     * @return Validator
     */
    private function validateCreateFormValues($values)
    {
        $validator = Validator::init($values['name'], 'name')
            ->isString()
            ->isMatch('/^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/', 'Invalid name');
        
        $validator->newValidation($values['email'], 'email')
            ->isEmail();

        $validator->newValidation($values['task'], 'task')
            ->isNotEmptyString();

        return $validator;
    }

    public function delete()
    {
        Auth::check();
        
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
            return App::getInstance()->redirect(
                static::getLastPageUrl()
            );
        }

        // An error was during query
        App::getInstance()->setStatusNotFound();
        return View::render('notfound', [
            'tasksUrl' => static::getLastPageUrl(),
        ]);
    }

    public function done()
    {
        Auth::check();
        
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

        // Success marked
        if ($result) {
            return App::getInstance()->redirect(
                static::getLastPageUrl()
            );
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

    private function getPerPageSettingsFromRequest()
    {
        $_SESSION['perPage'] = filter_input(INPUT_POST, 'perPage', FILTER_SANITIZE_NUMBER_INT) ?? $_SESSION['perPage'];
    }

    public static function getLastPageUrl()
    {
        $lastPageNumber = $_SESSION['tasksPageNumber'] ?? 1;
        if ($lastPageNumber > 1) {
            return url("tasks/{$lastPageNumber}");
        }
        return url('/');
    }
}
