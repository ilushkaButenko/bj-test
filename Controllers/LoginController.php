<?php

namespace iButenko\Controllers;

use iButenko\App\Controller;
use iButenko\App\Validator;
use iButenko\App\View;
use iButenko\App\Auth;

/**
 * LoginController
 */
class LoginController extends Controller
{
    public function index()
    {
        if (empty($_POST)) {
            return View::render('login', [
                'tasksUrl' => TaskController::getLastPageUrl(),
            ]);
        }
        
        $filteredInput = static::filterHtmlInput($_POST);

        $validator = Validator::init($filteredInput['login'], 'login')
            ->isNotEmptyString()
            ->isMatch('/^[a-zA-Z]+$/', 'Login may contain only letters');
        $validator->newValidation($filteredInput['password'], 'password')
            ->isNotEmptyString();
        
        if ($validator->hasErrors()) {
            return View::render('login', [
                'oldInput' => $filteredInput,
                'errors' => $validator->getErrors(),
                'tasksUrl' => TaskController::getLastPageUrl(),
            ]);
        }

        // Auth
        if (Auth::login($filteredInput['login'], $filteredInput['password'])) {
            return View::render('login-success', [
                'tasksUrl' => TaskController::getLastPageUrl(),
            ]);
        }

        View::render('login', [
            'authFail' => true,
            'tasksUrl' => TaskController::getLastPageUrl(),
        ]);
    }
}
