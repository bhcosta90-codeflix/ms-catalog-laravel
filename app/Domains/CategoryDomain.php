<?php

namespace App\Domains;

use App\Domains\Exceptions\EntityValidationException;
use App\Domains\Validations\DomainValidation;

final class CategoryDomain
{
    use Traits\MagicMethodsTrait;

    public function __construct(
        protected string $id = "",
        protected string $name = "",
        protected string|null $description = "",
        protected bool $isActive = true
    ) {
        $this->validated();
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function enable(): void
    {
        $this->isActive = true;
    }

    public function update(
        string $name,
        string|null $description
    ) {
        $this->name = $name;
        $this->description = $description;
    }

    public function validated()
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name, 2);
        DomainValidation::strCanNullAndMinLength($this->description);
        DomainValidation::strCanNullAndMaxLength($this->description);
    }
}
