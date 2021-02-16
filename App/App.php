<?php

namespace iButenko\App;

use iButenko\App\Router;

/**
 * App
 * 
 * Singleton class representing an app.
 */
class App
{
    // Instance
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new App();
        }
        return self::$instance;
    }

    // db connection
    private $db = null;

    private $router = null;

    /**
     * Run
     * 
     * Run application
     *
     * @return void
     */
    public function Run()
    {
        session_start();

        $this->connectToDatabase();

        $this->router = new Router();
        $this->router->findRealRoute($_SERVER['REQUEST_URI'], BASE_URI);

        // Run controller method
        $className = $this->router->getControllerClassName();
        $controller = new $className($this->router->getArgument());
        $controller->{$this->router->getMethodName()}();
    }
    
    /**
     * connectToDatabase
     *
     * @return void
     */
    private function connectToDatabase()
    {
        $this->db = new \PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS);
    }
    
    /**
     * getDatabase
     *
     * @return PDO
     */
    public function getDatabase(): \PDO
    {
        return $this->db;
    }
    
    /**
     * getRouter
     *
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }
    
    /**
     * redirect
     * 
     * Changes header location, substitudes base uri.
     *
     * @param  mixed $uri uri to go
     * @param  mixed $code http code
     * @return void
     */
    public function redirect($uri, $code = 301)
    {
        header('Location: ' . BASE_URI . $uri, true, $code);
    }
    
    /**
     * setStatusNotFound
     * 
     * Sets header 404
     *
     * @return void
     */
    public function setStatusNotFound()
    {
        header('HTTP/1.0 404', true, 404);
    }
    
    /**
     * setStatusForbidden
     * 
     * Sets 403 header
     *
     * @return void
     */
    public function setStatusForbidden()
    {
        header('HTTP/1.0 403', true, 403);
    }
    
    /**
     * setStatusError
     * 
     * Sets 500 header
     *
     * @return void
     */
    public function setStatusError()
    {
        header('HTTP/1.0 500', true, 500);
    }
}
