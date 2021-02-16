<?php

namespace iButenko\App;

/**
 * Auth
 * 
 * Provides auth operations
 */
class Auth
{
    private static $expectedPassword = '$2y$10$ADPmb8BBtJp2GFI.qfaYwufEzf6Iv8KthUyUOfmfb4qoApxZNMLwW';
    
    /**
     * check
     *
     * @param  boolean $showError throw 403 error if not authorised
     * @return boolean|void
     */
    public static function check($showError = true)
    {
        if ($showError && $_SESSION['auth'] !== true) {
            App::getInstance()->setStatusForbidden();
            View::render('notauthorised');
            exit();
        }
        return $_SESSION['auth'] === true;
    }
    
    /**
     * login
     * 
     * Authentificates user and remembers it
     *
     * @param  mixed $login
     * @param  mixed $password
     * @return boolean success
     */
    public static function login($login, $password)
    {
        if ($login === 'admin' && password_verify($password, self::$expectedPassword)) {
            $_SESSION['auth'] = true;
            return true;
        }
        $_SESSION['auth'] = false;
        return false;
    }
    
    /**
     * logout
     * 
     * Forgets user
     *
     * @return void
     */
    public static function logout()
    {
        $_SESSION['auth'] = false;
    }
}