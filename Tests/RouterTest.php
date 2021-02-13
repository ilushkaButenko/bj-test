<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use iButenko\App\Router;

final class RouterTest extends TestCase
{
    public function testAllIsParsed(): void
    {
        $router = new Router();

        // Without $baseUri
        $router->parseUri('task/list/2', '/');
        $this->assertEquals('task', $router->getControllerName());
        $this->assertEquals('list', $router->getMethodName());
        $this->assertEquals('2', $router->getArgument());

        // With $baseUri
        $router->parseUri('/somefolder/someplace/task/list/2', '/somefolder/someplace');
        $this->assertEquals('task', $router->getControllerName());
        $this->assertEquals('list', $router->getMethodName());
        $this->assertEquals('2', $router->getArgument());

        // Uri is longer that needle
        $router->parseUri('/task/list/somearg/extra-uri', '/');
        $this->assertEquals('task', $router->getControllerName());
        $this->assertEquals('list', $router->getMethodName());
        $this->assertEquals('somearg', $router->getArgument());
    }
    public function testShashAtStringEnd(): void
    {
        $router = new Router();

        // "/" in $baseUri and $uri
        $router->parseUri('/my-projects/testing/mvc/tasks/', '/my-projects/testing/mvc/');

        $this->assertEquals('tasks', $router->getControllerName());
        $this->assertEquals('', $router->getMethodName());
        $this->assertEquals('', $router->getArgument());

        // "/" in $baseUri
        $router->parseUri('/my-projects/testing/mvc/tasks/', '/my-projects/testing/mvc');

        $this->assertEquals('tasks', $router->getControllerName());
        $this->assertEquals('', $router->getMethodName());
        $this->assertEquals('', $router->getArgument());

        // "/" in $uri
        $router->parseUri('/my-projects/testing/mvc/tasks', '/my-projects/testing/mvc/');

        $this->assertEquals('tasks', $router->getControllerName());
        $this->assertEquals('', $router->getMethodName());
        $this->assertEquals('', $router->getArgument());

        // For last test look testSlashAtStringBegin 
    }

    public function testSlashAtStringBegin(): void
    {
        $router = new Router();

        // "/" in $baseUri and $uri
        $router->parseUri('/my-projects/testing/mvc/tasks', '/my-projects/testing/mvc');

        $this->assertEquals('tasks', $router->getControllerName());
        $this->assertEquals('', $router->getMethodName());
        $this->assertEquals('', $router->getArgument());

        // "/" in $baseUri
        $router->parseUri('/my-projects/testing/mvc/tasks', 'my-projects/testing/mvc');

        $this->assertEquals('tasks', $router->getControllerName());
        $this->assertEquals('', $router->getMethodName());
        $this->assertEquals('', $router->getArgument());

        // "/" in $uri
        $router->parseUri('my-projects/testing/mvc/tasks', '/my-projects/testing/mvc');

        $this->assertEquals('tasks', $router->getControllerName());
        $this->assertEquals('', $router->getMethodName());
        $this->assertEquals('', $router->getArgument());

        // No "/" at begin of $uri and $baseUri
        $router->parseUri('my-projects/testing/mvc/tasks', 'my-projects/testing/mvc');

        $this->assertEquals('tasks', $router->getControllerName());
        $this->assertEquals('', $router->getMethodName());
        $this->assertEquals('', $router->getArgument());
    }

    public function testParseWithoutBaseUri(): void
    {
        $router = new Router();
        $router->parseUri('/task', '/');

        $this->assertEquals('task', $router->getControllerName());
    }
}