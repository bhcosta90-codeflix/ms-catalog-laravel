<?php

namespace Tests\Unit\App\Models;

use App\Models\Genre;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

class GenreTest extends Abstracts\ModelTestCase
{
    protected function model(): Model
    {
        return new Genre();
    }

    protected function fillables(): array
    {
        return [
            'id',
            'name',
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
