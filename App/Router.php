<?php

namespace iButenko\App;

/**
 * - App routing: getting controller class and method
 */
class Router
{
    private $controllerName = '';
    private $methodName = '';
    private $argument = '';

    public function parseUri($uri, $baseUri)
    {
        // Add slash if there is no slash
        $baseUri = $baseUri[0] === '/' ? $baseUri : '/'.$baseUri;
        $uri = $uri[0] === '/' ? $uri : '/'.$uri;

        // Remove base uri from the uri
        $uri = substr($uri, strlen($baseUri));

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
}
