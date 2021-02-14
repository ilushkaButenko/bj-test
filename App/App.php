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

    /**
     * Run
     * 
     * Run application
     *
     * @return void
     */
    public function Run()
    {
        $this->connectToDatabase();

        $router = new Router();
        $router->findRealRoute($_SERVER['REQUEST_URI'], BASE_URI);

        // Run controller method
        $className = $router->getControllerClassName();
        $controller = new $className($router->getArgument());
        $controller->{$router->getMethodName()}();
    }

    private function connectToDatabase()
    {
        $this->db = new \PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS);
    }

    public function getDatabase(): \PDO
    {
        return $this->db;
    }
}
