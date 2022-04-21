<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
    ];

    public $fillable = [
        'id',
        'name',
        'type',
    ];
}
