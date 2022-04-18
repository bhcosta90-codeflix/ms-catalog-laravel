<?php

namespace Tests\Unit\App\Models\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;

    abstract protected function traits(): array;

    abstract protected function fillables(): array;

    abstract protected function casts(): array;

    public function testIfUseTraits()
    {
        $need = $this->traits();
        $traits = array_keys(class_uses($this->model()));
        $this->assertEquals($need, $traits);
    }

    public function testIncrementing()
    {
        $model = $this->model();
        $this->assertFalse($model->incrementing);
    }


    public function testCasts()
    {
        $need = $this->casts();
        $this->assertEquals($need, $this->model()->getCasts());
    }

    public function testFillable()
    {
        $need = $this->fillables();
        $this->assertEquals($need, $this->model()->getFillable());
    }
}
