<?php

namespace iButenko\App;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * Router
 * 
 * Search requested controller class and method.
 */
class Router
{
    const controllerPostfix = 'Controller';
    const controllerPrefix = 'iButenko\Controllers\\';
    const defaultMethodName = 'index';
    const notFoundControllerName = 'NotFound';
    
    private $controllerName = '';
    private $methodName = '';
    private $argument = '';
    private $arguments = [];
    private $controllerClassName = '';
    private $clearUri = '';
    private $parameters = '';
    
    /**
     * findRealRoute
     * 
     * Finds out controller class and method. If controller or method
     * doesn't exist it looks for suitable controller and method replace.
     *
     * @param  mixed $fullUri
     * @param  mixed $baseUri
     * @return void
     */
    public function findRealRoute($fullUri, $baseUri)
    {
        $this->parseUri($fullUri, $baseUri);

        // Parse uri
        if (!$this->getDefinedRoute()) {
            // $this->getAutoRoute();
        }

        $this->controllerClassName = self::controllerNameToClassName($this->controllerName);

        if (!class_exists($this->controllerClassName)) {
            $this->setNotFound();
        }

        if ($this->methodName === '') {
            $this->methodName = self::defaultMethodName;
        }

        if (!method_exists($this->controllerClassName, $this->methodName)) {
            $this->setNotFound();
        }
    }

    public function setNotFound()
    {
        $this->controllerName = self::notFoundControllerName;
        $this->methodName = self::defaultMethodName;
        $this->controllerClassName = self::controllerNameToClassName($this->controllerName);
    }

    /**
     * Finds controller name, method and argument from uri.
     * 
     * Default routing method.
     */
    public function getAutoRoute()
    {
        // Get controller name from the uri
        preg_match('/\/?([^\/]+)\/?([^\/]+)?\/?([^\/]+)?/', $this->clearUri, $matches);
        $this->controllerName = isset($matches[1]) ? $matches[1] : '';
        $this->methodName = isset($matches[2]) ? $matches[2] : '';
        $this->argument = isset($matches[3]) ? $matches[3] : '';
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getArgument()
    {
        return $this->argument;
    }

    public function getControllerClassName()
    {
        return $this->controllerClassName;
    }

    /**
     * Transform given controller name to full class controller name
     */
    public static function controllerNameToClassName($controllerName)
    {
        return self::controllerPrefix.ucfirst($controllerName).self::controllerPostfix;
    }
    
    /**
     * getClearUri
     * 
     * Get uri without get parameters
     *
     * @return string
     */
    public function getClearUri()
    {
        return $this->clearUri;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Finds out which defined route is match.
     */
    public function getDefinedRoute()
    {
        $routes = require(RESOURCE_DIRECTORY . '/Routes.php');

        foreach ($routes as $route) {
            if ($route->isMatch($this->clearUri)) {
                $this->controllerName = $route->getControllerName();
                $this->methodName = $route->getMethodName();
                $this->argument = $route->getArguments()[0];
                $this->arguments = $route->getArguments();

                return true;
            }
        }

        return false;
    }

    /**
     * Removes base uri substring from uri
     * 
     * @param string $fullUri
     * @param string $baseUri
     */
    public static function removeBaseUri($fullUri, $baseUri)
    {
        // Add slash if there is no slash
        $baseUri = $baseUri[0] === '/' ? $baseUri : '/'.$baseUri;
        $uri = $fullUri[0] === '/' ? $fullUri : '/'.$fullUri;

        // Remove base uri from the uri
        $uri = substr($uri, strlen($baseUri));

        return $uri;
    }

    /**
     * Separate parameters and set clear uri.
     * 
     * @param string $fullUri
     * @param string $baseUri
     */
    public function parseUri($fullUri, $baseUri)
    {
        $uri = self::removeBaseUri($fullUri, $baseUri);

        // Remove get parameters
        if (strpos($uri, '?') !== false) {
            $this->parameters = substr($uri, strpos($uri, '?'));
            $this->clearUri = substr($uri, 0, strpos($uri, '?'));
        } else {
            $this->clearUri = $uri;
        }
    }
}
