<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use iButenko\App\Validator;

final class ValidatorTest extends TestCase
{
    public function testIsString(): void
    {
        $res = Validator::init('some string')->isString()->error();
        $this->assertEquals(false, $res);

        $res = Validator::init(123)->isString()->error();
        $this->assertNotEquals(false, $res);

        $res = Validator::init(false)->isString()->error();
        $this->assertNotEquals(false, $res);
    }

    public function testIsMatch(): void
    {
        $res = Validator::init('Ivan')->isMatch('/[A-Z][a-z]+/')->error();
        $this->assertEquals(false, $res);

        $res = Validator::init('ivan')->isMatch('/[A-Z][a-z]+/')->error();
        $this->assertNotEquals(false, $res);
    }
}