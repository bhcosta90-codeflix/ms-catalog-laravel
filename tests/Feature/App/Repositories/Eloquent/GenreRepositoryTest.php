<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre as Model;
use App\Models\Genre;
use App\Repositories\Eloquent\GenreRepository as Repository;
use Costa\Core\Modules\Genre\Entities\Genre as Entity;
use Costa\Core\Modules\Genre\Repositories\GenreRepositoryInterface as RepositoryInterface;
use Costa\Core\Utils\Domains\Exceptions\NotFoundDomainException;
use Costa\Core\Utils\Domains\Repositories\PaginationInterface;
use Costa\Core\Utils\Domains\ValueObject\Uuid;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class GenreRepositoryTest extends TestCase
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
        $model = new Entity(name: 'bruno costa');

        $response = $this->repository->insert($model);

        $this->assertInstanceOf(RepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(Entity::class, $response);
        $this->assertEquals($response->id, $model->id);
        $this->assertDatabaseHas('genres', [
            'id' => $model->id,
            'name' => $model->name,
        ]);
    }

    public function testInsertDisabled()
    {
        $model = new Entity(name: 'bruno costa');
        $model->disable();

        $response = $this->repository->insert($model);

        $this->assertFalse($response->isActive);
        $this->assertDatabaseHas('genres', [
            'id' => $model->id,
            'name' => $model->name,
            'is_active' => false
        ]);
    }

    public function testNotFound()
    {
        $this->expectException(NotFoundDomainException::class);
        $this->repository->findById('fake-value');
    }

    public function testFindById()
    {
        $genre = Genre::factory()->create();

        $response = $this->repository->findById($genre->id);
        $this->assertEquals($genre->id, $response->id);
        $this->assertEquals($genre->name, $response->name);
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

    public function testPaginate()
    {
        Model::factory(35)->create();

        $response = $this->repository->paginate();
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());

        $response = $this->repository->paginate(page: 3);
        $this->assertCount(5, $response->items());
    }

    public function testPaginateEmpty()
    {
        $response = $this->repository->paginate();
        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
    }

    public function testUpdate()
    {
        $modelDb = Model::factory()->create();

        $model = new Entity(
            id: new Uuid($modelDb->id),
            name: 'teste',
        );

        $response = $this->repository->update($model);

        $this->assertInstanceOf(Entity::class, $response);
        $this->assertNotEquals($model->name, $modelDb->name);
        $this->assertEquals('teste', $response->name);

        $this->assertDatabaseHas('genres', [
            'id' => $model->id,
            'name' => $model->name,
        ]);
    }

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundDomainException::class);

        $model = new Entity(
            name: 'teste',
        );

        $this->repository->update($model);
    }

    public function testDelete()
    {
        $modelDb = Model::factory()->create();

        $model = new Entity(
            id: new Uuid($modelDb->id),
            name: 'teste',
        );

        $response = $this->repository->delete($model);
        $this->assertTrue($response);
        $this->assertSoftDeleted($modelDb);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundDomainException::class);

        $model = new Entity(
            name: 'teste',
        );

        $this->repository->delete($model);
    }

    public function testInsertWithCategories()
    {
        $categories = Category::factory(4)->create()->pluck('id')->toArray();

        $model = new Entity(name: 'bruno costa');

        foreach ($categories as $category) {
            $model->addCategory($category);
        }

        $this->repository->insert($model);

        $this->assertDatabaseCount('category_genre', 4);
    }

    public function testInsertWithCategoryNotFound()
    {
        $this->expectException(QueryException::class);

        $model = new Entity(name: 'bruno costa');
        $model->addCategory((string) Uuid::random());
        $this->repository->insert($model);
        $this->assertDatabaseCount('category_genre', 4);
    }

    public function testUpdateWithCategories()
    {
        $categories = Category::factory(4)->create()->pluck('id')->toArray();
        $modelDb = Model::factory()->create();
        $model = new Entity(
            id: new Uuid($modelDb->id),
            name: 'teste',
            categories: $categories,
        );

        $this->repository->update($model);
        $this->assertDatabaseCount('category_genre', 4);
    }

    public function testUpdateWithCategoriesNotFound()
    {
        $this->expectException(QueryException::class);

        $modelDb = Model::factory()->create();
        $model = new Entity(
            id: new Uuid($modelDb->id),
            name: 'teste',
            categories: ['fake-id'],
        );

        $this->repository->update($model);
    }
}
