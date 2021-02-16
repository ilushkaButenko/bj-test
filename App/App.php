<?php

namespace iButenko\App;

use iButenko\App\Router;

/**
 * App
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

    private function connectToDatabase()
    {
        $this->db = new \PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS);
    }

    public function getDatabase(): \PDO
    {
        return $this->db;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function redirect($uri, $code = 301)
    {
        header('Location: ' . BASE_URI . $uri, true, $code);
    }

    public function setStatusNotFound()
    {
        header('HTTP/1.0 404', true, 404);
    }

    public function setStatusForbidden()
    {
        header('HTTP/1.0 403', true, 403);
    }

    public function setStatusError()
    {
        header('HTTP/1.0 500', true, 500);
    }
}
