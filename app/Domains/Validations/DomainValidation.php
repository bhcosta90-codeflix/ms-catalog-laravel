<?php

namespace App\Domains\Validations;

use App\Domains\Exceptions\EntityValidationException;

final class DomainValidation
{
    public static function notNull(string $value, string $exceptionMessage = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptionMessage ?? "Should not be empty value");
        }
    }

    public static function strCanNullAndMaxLength(string $value, int $length = 255, string $exceptionMessage = null)
    {
        !empty($value) && self::strMaxLength($value, $length);
    }

    public static function strCanNullAndMinLength(string $value, int $length = 2, string $exceptionMessage = null)
    {
        !empty($value) && self::strMinLength($value, $length);
    }

    public static function strMaxLength(string $value, int $length = 255, string $exceptionMessage = null)
    {
        if (strlen($value) > $length) {
            throw new EntityValidationException($exceptionMessage ?? "The value must not be greater than {$length} characters");
        }
    }

    public static function strMinLength(string $value, int $length = 2, string $exceptionMessage = null)
    {
        if (strlen($value) < $length) {
            throw new EntityValidationException($exceptionMessage ?? "The value must at least {$length} characters");
        }
    }

}
