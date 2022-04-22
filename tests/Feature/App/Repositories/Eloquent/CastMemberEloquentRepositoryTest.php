<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Eloquent\CastMemberRepository as Repository;
use App\Repositories\Eloquent\CastMemberRepository;
use Costa\Core\Modules\CastMember\Entities\CastMember as Entity;
use Costa\Core\Modules\CastMember\Enums\CastMemberType;
use Costa\Core\Modules\CastMember\Repositories\CastMemberRepositoryInterface as RepositoryInterface;
use Costa\Core\Utils\Exceptions\NotFoundDomainException;
use Costa\Core\Utils\Contracts\PaginationInterface;
use Costa\Core\Utils\ValueObject\Uuid;
use Tests\TestCase;

class CastMemberEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Repositories\Eloquent\CastMemberRepository */
        $this->repository = new Repository(new Model());
    }

    public function testImplementInterfaceRepository()
    {
        $this->assertInstanceOf(RepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new Entity(
            name: 'Teste',
            type: CastMemberType::ACTOR,
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(Entity::class, $response);
        $this->assertDatabaseHas('cast_members', [
            'id' => $entity->id(),
            'name' => $entity->name,
            'type' => $entity->type->value,
        ]);
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundDomainException::class);
        $this->repository->findById('fake-value');
    }

    public function testFindById()
    {
        $genre = Model::factory()->create();

        $response = $this->repository->findById($genre->id);
        $this->assertEquals($genre->id, $response->id);
        $this->assertEquals($genre->name, $response->name);
        $this->assertEquals($genre->type, $response->type->value);
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

    public function testUpdateNotFound()
    {
        $this->expectException(NotFoundDomainException::class);

        $model = new Entity(
            name: 'teste',
            type: CastMemberType::ACTOR,
        );

        $this->repository->update($model);
    }

    public function testUpdate()
    {
        $modelDb = Model::factory()->create();

        $model = new Entity(
            id: new Uuid($modelDb->id),
            name: 'teste',
            type: CastMemberType::ACTOR,
        );

        $response = $this->repository->update($model);

        $this->assertInstanceOf(Entity::class, $response);
        $this->assertNotEquals($model->name, $modelDb->name);
        $this->assertEquals('teste', $response->name);

        $this->assertDatabaseHas('cast_members', [
            'id' => $model->id(),
            'name' => $model->name,
        ]);
    }

    public function testDeleteNotFound() {
        $this->expectException(NotFoundDomainException::class);

        $model = new Entity(
            name: 'teste',
            type: CastMemberType::ACTOR
        );

        $this->repository->delete($model);
    }

    public function testDelete() {
        $modelDb = Model::factory()->create();

        $model = new Entity(
            id: new Uuid($modelDb->id),
            name: 'teste',
            type: CastMemberType::ACTOR
        );

        $response = $this->repository->delete($model);

        $this->assertTrue($response);
        $this->assertSoftDeleted($modelDb);
    }
}
