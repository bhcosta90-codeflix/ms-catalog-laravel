<?php

namespace App\Domains\Traits;

use App\Domains\Exceptions\PropertyException;

trait MagicMethodsTrait
{
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        $nameClass = get_class($this);
        throw new PropertyException("Property {$name} not found in class {$nameClass}");
    }
}
