<?php

namespace Tests\Unit\App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

class CategoryTest extends Abstracts\ModelTestCase
{
    protected function model(): Model
    {
        return new Category();
    }

    protected function fillables(): array
    {
        return [
            'id',
            'name',
            'description',
            'is_active',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime'
        ];
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class,
        ];
    }
}
