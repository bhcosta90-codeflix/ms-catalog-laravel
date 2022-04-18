<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean',
    ];

    public $incrementing = false;
}
