<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryRepository as Repository;
use Costa\Core\Domains\Entities\Category as Entity;
use Costa\Core\Domains\Repositories\CategoryRepositoryInterface;
use Costa\Core\Domains\Exceptions\NotFoundDomainException;
use Costa\Core\Domains\Repositories\PaginationInterface;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Repositories\Eloquent\CategoryRepository */
        $this->repository = new Repository(new Model());
    }

    public function testInsert()
    {
        $entity = new Entity(
            name: 'Teste'
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(Entity::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name,
        ]);
    }

    public function testFindById()
    {
        $category = Model::factory()->create();

        $response = $this->repository->findById($category->id);
        $this->assertInstanceOf(Entity::class, $response);
        $this->assertEquals($category->id, $response->id());
    }

    public function testFindAll()
    {
        Model::factory(10)->create();
        $response = $this->repository->findAll();
        $this->assertCount(10, $response);
    }

    public function testFindAllEmpty()
    {
        $response = $this->repository->findAll();
        $this->assertCount(0, $response);
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundDomainException::class);
        $this->repository->findById('fake-value');
    }

    public function testPaginate()
    {
        Model::factory(100)->create();

        $response = $this->repository->paginate();
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
    }

    public function testUpdate() {
        $categoryDb = Model::factory()->create();

        $category = new Entity(
            id: $categoryDb->id,
            name: 'teste',
        );

        $response = $this->repository->update($category);

        $this->assertInstanceOf(Entity::class, $response);
        $this->assertNotEquals($category->name, $categoryDb->name);
        $this->assertEquals('teste', $response->name);
    }

    public function testUpdateNotFound() {
        $this->expectException(NotFoundDomainException::class);

        $category = new Entity(
            name: 'teste',
        );

        $this->repository->update($category);
    }
}
