<?php

namespace iButenko\App;

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
    private $controllerClassName = '';
    private $clearUri = '';
    private $parameters = '';
    
    /**
     * findRealRoute
     * 
     * Finds out controller class and method. If controller or method
     * doesn't exist it looks for suitable controller and method replace.
     *
     * @param  mixed $uri
     * @param  mixed $baseUri
     * @return void
     */
    public function findRealRoute($uri, $baseUri)
    {
        // Parse uri
        $this->parseUri($uri, $baseUri);

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

    public function parseUri($uri, $baseUri)
    {
        // Add slash if there is no slash
        $baseUri = $baseUri[0] === '/' ? $baseUri : '/'.$baseUri;
        $uri = $uri[0] === '/' ? $uri : '/'.$uri;

        // Remove base uri from the uri
        $uri = substr($uri, strlen($baseUri));

        // Remove get parameters
        if (strpos($uri, '?') !== false) {
            $this->parameters = substr($uri, strpos($uri, '?'));
            $this->clearUri = substr($uri, 0, strpos($uri, '?'));
            $uri = $this->clearUri;
        } else {
            $this->clearUri = $uri;
        }

        // Get controller name from the uri
        preg_match('/\/?([^\/]+)\/?([^\/]+)?\/?([^\/]+)?/', $uri, $matches);
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
}
