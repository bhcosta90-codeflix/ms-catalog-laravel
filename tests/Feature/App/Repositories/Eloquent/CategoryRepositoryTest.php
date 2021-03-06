<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryRepository as Repository;
use Costa\Core\Modules\Category\Entities\Category as Entity;
use Costa\Core\Modules\Category\Repositories\CategoryRepositoryInterface as RepositoryInterface;
use Costa\Core\Utils\Exceptions\NotFoundDomainException;
use Costa\Core\Utils\Contracts\PaginationInterface;
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

        $this->assertInstanceOf(RepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(Entity::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name,
        ]);
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundDomainException::class);
        $this->repository->findById('fake-value');
    }

    public function testFindById()
    {
        $model = Model::factory()->create();

        $response = $this->repository->findById($model->id);
        $this->assertInstanceOf(Entity::class, $response);
        $this->assertEquals($model->id, $response->id());
    }

    public function testFindAllEmpty()
    {
        $response = $this->repository->findAll();
        $this->assertCount(0, $response);
    }

    public function testFindAll()
    {
        Model::factory(10)->create();
        $response = $this->repository->findAll();
        $this->assertCount(10, $response);
    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
    }

    public function testPaginate()
    {
        Model::factory(35)->create();

        $response = $this->repository->paginate();
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());

        $response = $this->repository->paginate(page: 3);
        $this->assertCount(5, $response->items());
    }

    public function testUpdateNotFound() {
        $this->expectException(NotFoundDomainException::class);

        $model = new Entity(
            name: 'teste',
        );

        $this->repository->update($model);
    }

    public function testUpdate() {
        $modelDb = Model::factory()->create();

        $model = new Entity(
            id: $modelDb->id,
            name: 'teste',
        );

        $response = $this->repository->update($model);

        $this->assertInstanceOf(Entity::class, $response);
        $this->assertNotEquals($model->name, $modelDb->name);
        $this->assertEquals('teste', $response->name);
    }

    public function testDeleteNotFound() {
        $this->expectException(NotFoundDomainException::class);

        $model = new Entity(
            name: 'teste',
        );

        $this->repository->delete($model);
    }

    public function testDelete() {
        $modelDb = Model::factory()->create();

        $model = new Entity(
            id: $modelDb->id,
            name: 'teste',
        );

        $response = $this->repository->delete($model);

        $this->assertTrue($response);
        $this->assertSoftDeleted($modelDb);
    }
}
