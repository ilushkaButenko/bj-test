<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use iButenko\App\Validator;

final class ValidatorTest extends TestCase
{
    public function testIsString(): void
    {
        $res = Validator::init('some string')->isString()->getError();
        $this->assertEquals(false, $res);

        $res = Validator::init(123)->isString()->getError();
        $this->assertNotEquals(false, $res);

        $res = Validator::init(false)->isString()->getError();
        $this->assertNotEquals(false, $res);
    }

    public function testIsMatch(): void
    {
        $res = Validator::init('Ivan')->isMatch('/[A-Z][a-z]+/')->getError();
        $this->assertEquals(false, $res);

        $res = Validator::init('ivan')->isMatch('/[A-Z][a-z]+/')->getError();
        $this->assertNotEquals(false, $res);
    }

    public function testOneByOne(): void
    {
        $val = Validator::init('Ivan', 'name')->isMatch('/[A-Z][a-z]+/')
            ->newValidation('Kalita', 'last_name')->isMatch('/[A-Z][a-z]+/')
            ->getErrors();
        $this->assertEquals([
            'name' => false,
            'last_name' => false
        ], $val);

        $val = Validator::init('Ivan', 'name')->isMatch('/[A-Z][a-z]+/')
            ->newValidation('kalita', 'last_name')->isMatch('/[A-Z][a-z]+/')
            ->newValidation('Kkkalita', 'laaast_name')->isMatch('/[A-Z][a-z]+/')
            ->getErrors();
        $this->assertEquals([
            'name' => false,
            'last_name' => 'Invalid value',
            'laaast_name' => false
        ], $val);
    }
}