<?php

namespace iButenko\Controllers;

use iButenko\App\Controller;
use iButenko\App\Validator;
use iButenko\App\View;
use iButenko\App\App;

/**
 * LogoutController
 */
class LogoutController extends Controller
{
    public function index()
    {
        if ($_SESSION['auth']) {
            $_SESSION['auth'] = false;
            return View::render('logout-success');
        }
        App::getInstance()->setStatusForbidden();
        View::render('notauthorised');
    }
}
