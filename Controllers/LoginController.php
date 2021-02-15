<?php

namespace iButenko\Controllers;

use iButenko\App\Controller;
use iButenko\App\Validator;
use iButenko\App\View;

/**
 * LoginController
 */
class LoginController extends Controller
{
    private $expectedPassword = '$2y$10$ADPmb8BBtJp2GFI.qfaYwufEzf6Iv8KthUyUOfmfb4qoApxZNMLwW';
    public function index()
    {
        if (empty($_POST)) {
            return View::render('login');
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
                'errors' => $validator->getErrors()
            ]);
        }

        // Auth
        if ($filteredInput['login'] === 'admin' && password_verify($filteredInput['password'], $this->expectedPassword)) {
            $_SESSION['auth'] = true;
            return View::render('login-success');
        }

        View::render('login', [
            'authFail' => true
        ]);
    }
}
