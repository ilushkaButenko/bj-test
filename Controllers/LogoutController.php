<?php

namespace iButenko\Controllers;

use iButenko\App\Controller;
use iButenko\App\View;
use iButenko\App\Auth;

/**
 * LogoutController
 */
class LogoutController extends Controller
{
    public function index()
    {
        // authorised only
        Auth::check();

        Auth::logout();
        View::render('logout-success', [
            'tasksUrl' => TaskController::getLastPageUrl(),
        ]);
    }
}
