<?php

namespace Tests\Unit\Domains;

use App\Domains\CategoryDomain;
use App\Domains\Exceptions\EntityValidationException;
use PHPUnit\Framework\TestCase;

class CategoryDomainTest extends TestCase
{
    public function testAttributes()
    {
        $category = new CategoryDomain(
            name: "teste",
            description: "New desc",
            isActive: true
        );

        $this->assertEquals('teste', $category->name);
        $this->assertEquals('New desc', $category->description);
        $this->assertTrue($category->isActive);
    }

    public function testDisabled()
    {
        $category = new CategoryDomain(
            name: "teste"
        );

        $this->assertTrue($category->isActive);

        $category->disable();

        $this->assertFalse($category->isActive);
    }

    public function testEnabled()
    {
        $category = new CategoryDomain(
            name: "teste",
            isActive: false
        );

        $this->assertFalse($category->isActive);

        $category->enable();

        $this->assertTrue($category->isActive);
    }

    public function testUpdate()
    {
        $uuid = 'hash.value';

        $category = new CategoryDomain(
            id: $uuid,
            name: "teste",
            description: "New desc",
            isActive: true
        );

        $category->update(
            name: "new_name",
            description: "new_desc"
        );

        $this->assertEquals('new_name', $category->name);
        $this->assertEquals('new_desc', $category->description);

        $category->update(
            name: "new_name",
            description: null
        );

        $this->assertEquals('new_name', $category->name);
        $this->assertNull($category->description);
    }

    public function testExceptionName()
    {
        $this->expectException(EntityValidationException::class);

        new CategoryDomain(
            name: "t",
            description: "New desc",
            isActive: true
        );
    }

    public function testExceptionDescriptionMinLength()
    {
        $this->expectException(EntityValidationException::class);
        new CategoryDomain(
            name: "teste",
            description: "1",
        );
    }

    public function testExceptionDescriptionMaxLength()
    {
        $this->expectException(EntityValidationException::class);
        new CategoryDomain(
            name: "teste",
            description: str_repeat("1", 300),
        );
    }
}
