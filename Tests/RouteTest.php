<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use iButenko\App\Route;

final class RouteTest extends TestCase
{
    public function testAll(): void
    {
        $this->assertEquals(true, (new Route('test', 'Test', 'index', 0))->isMatch('/test'));
        $this->assertEquals(true, (new Route('test', 'Test', 'index', 0))->isMatch('test'));
        $this->assertEquals(false, (new Route('test', 'Test', 'index', 0))->isMatch('user'));
        $this->assertEquals(false, (new Route('user/show', 'Test', 'index', 0))->isMatch('test'));
        $this->assertEquals(false, (new Route('user/show', 'Test', 'index', 0))->isMatch('user/show/data'));
        $this->assertEquals(true, (new Route('user/show/%', 'Test', 'index', 0))->isMatch('user/show/data'));
    }
}
