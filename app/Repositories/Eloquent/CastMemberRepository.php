<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use Costa\Core\Modules\CastMember\Entities\CastMember as Entity;
use Costa\Core\Modules\CastMember\Repositories\CastMemberRepositoryInterface;
use Costa\Core\Utils\Contracts\PaginationInterface;

class CastMemberRepository implements CastMemberRepositoryInterface
{
    public function __construct(private Model $model)
    {
        //
    }


    public function insert(Entity $entity): Entity
    {

    }

    public function findById(string $id): Entity
    {

    }

    public function getIds(array $id = []): array
    {

    }

    public function findAll(array $filters = [], string|null $orderColumn = null, string|null $order = null): array
    {

    }

    public function paginate(
        array $filters = [],
        string|null $orderColumn = null,
        string|null $order = null,
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface
    {

    }

    public function update(Entity $entity): Entity
    {

    }

    public function delete(Entity $entity): bool
    {

    }

    public function toEntity(object $object): Entity
    {
        return new Entity(
            name: $object->name,
            type: $object->type,
            id: $object->id,
            createdAt: $object->createdAt,
            updatedAt: $object->updatedAt,
        );
    }
}
