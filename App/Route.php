<?php

/**
 * Route scheme:
 * 
 * uri/%/uri2/%
 */

namespace iButenko\App;

class Route
{
    private $uri;
    private $controllerName;
    private $methodName;
    private $arguments;

    const ARGUMENT_PLACEHOLDER = '%';

    /**
     * 
     */
    public function __construct($uri, $controllerName, $methodName)
    {
        $this->uri = $uri;
        $this->controllerName = $controllerName;
        $this->methodName =$methodName;
        $this->arguments = [];
    }

    /**
     * isMatch
     * 
     * Checks that current uri is mach with this route.
     */
    public function isMatch($uri)
    {
        /**
         * tasks/args
         */
        $givenUri = explode('/', trim($uri, '/ '));
        $needUri = explode('/', trim($this->uri, '/ '));

        $urlElemtnsCount = count($givenUri);

        if ($urlElemtnsCount !== count($needUri)) {
            return false;
        }

        for ($i = 0; $i < $urlElemtnsCount; ++$i) {
            if ($needUri[$i] === '%') {
                $this->arguments[] = $givenUri[$i];
                continue;
            }
            if ($needUri[$i] !== $givenUri[$i]) {
                return false;
            }
        }

        return true;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }
}
