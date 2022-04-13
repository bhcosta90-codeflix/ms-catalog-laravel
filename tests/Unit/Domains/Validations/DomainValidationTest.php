<?php

namespace Tests\Unit\Domains\Validations;

use App\Domains\Exceptions\EntityValidationException;
use App\Domains\Validations\DomainValidation;
use PHPUnit\Framework\TestCase;

class DomainValidationTest extends TestCase
{
    public function testDefaultMessage()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Should not be empty value');

        DomainValidation::notNull('');
    }

    public function testNotNull()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('dkopakdaw');

        DomainValidation::notNull('', 'dkopakdaw');
    }

    public function testStrMaxLength()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The value must not be greater than 3 characters');

        DomainValidation::strMaxLength(str_repeat('a', 4), 3);
    }

    public function testStrMinLength()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The value must at least 3 characters');

        DomainValidation::strMinLength('ab', 3);
    }

    public function testStrCanNullAndMaxLength()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The value must not be greater than 3 characters');

        DomainValidation::strCanNullAndMaxLength(str_repeat('a', 4), 3);
    }

    public function testStrCanNullAndMinLength()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The value must at least 3 characters');

        DomainValidation::strCanNullAndMinLength('ab', 3);
    }
}
