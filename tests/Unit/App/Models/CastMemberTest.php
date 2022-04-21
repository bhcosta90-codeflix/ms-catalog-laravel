<?php

namespace Tests\Unit\App\Models;

use App\Models\CastMember;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

class CastMemberTest extends Abstracts\ModelTestCase
{
    protected function model(): Model
    {
        return new CastMember();
    }

    protected function fillables(): array
    {
        return [
            'id',
            'name',
            'type',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
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
