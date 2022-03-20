<?php

namespace iButenko\Controllers;

use iButenko\App\Controller;
use iButenko\App\View;
use iButenko\App\App;

/**
 * NotFoundController
 */
class NotFoundController extends Controller
{
    public function index()
    {
        App::getInstance()->setStatusNotFound();

        View::render('notfound', [
            'tasksUrl' => TaskController::getLastPageUrl(),
        ]);
    }
}
