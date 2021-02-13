<?php

namespace iButenko\App;

use iButenko\App\Router;

/**
 * App
 */
class App
{    
    /**
     * Run
     * 
     * Run application
     *
     * @return void
     */
    public function Run()
    {
        $router = new Router();
        $router->findRealRoute($_SERVER['REQUEST_URI'], BASE_URI);

        // Run controller method
        $className = $router->getControllerClassName();
        $controller = new $className($router->getArgument());
        $controller->{$router->getMethodName()}();
    }
}
