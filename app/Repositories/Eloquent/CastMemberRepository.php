<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Presenters\Paginator;
use Costa\Core\Modules\CastMember\Entities\CastMember as Entity;
use Costa\Core\Modules\CastMember\Enums\CastMemberType;
use Costa\Core\Modules\CastMember\Repositories\CastMemberRepositoryInterface;
use Costa\Core\Utils\Contracts\PaginationInterface;
use Costa\Core\Utils\Exceptions\NotFoundDomainException;
use Costa\Core\Utils\ValueObject\Uuid;

class CastMemberRepository implements CastMemberRepositoryInterface
{
    public function __construct(private Model $model)
    {
        //
    }


    public function insert(Entity $entity): Entity
    {
        $ret = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'type' => $entity->type->value,
        ]);

        return $this->toEntity($ret);
    }

    public function findById(string $id): Entity
    {
        if ($model = $this->findByDb($id)) {
            return $this->toEntity($model);
        }
    }

    public function getIds(array $id = []): array
    {
        return $this->model->whereIn('id', $id)->pluck('id')->toArray();
    }

    public function findAll(array $filters = [], string|null $orderColumn = null, string|null $order = null): array
    {
        $data = $this->model->get();
        return $data->toArray();
    }

    public function paginate(
        array $filters = [],
        string|null $orderColumn = null,
        string|null $order = null,
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface
    {
        $data = $this->model->paginate(
            perPage: $totalPage,
            page: $page,
        );

        return new Paginator($data);
    }

    public function update(Entity $entity): Entity
    {
        if ($model = $this->findByDb($entity->id())) {
            $model->update([
                'name' => $entity->name,
                'type' => $entity->type->value,
            ]);
            return $this->toEntity($model);
        }
    }

    public function delete(Entity $entity): bool
    {
        if ($model = $this->findByDb($entity->id())) {
            return $model->delete();
        }
    }

    protected function findByDb(string $id)
    {
        if ($model = $this->model->find($id)) {
            return $model;
        }

        throw new NotFoundDomainException(__('Genre not found'));
    }

    public function toEntity(object $object): Entity
    {
        return new Entity(
            name: $object->name,
            type: CastMemberType::from($object->type),
            id: new Uuid($object->id),
            createdAt: $object->created_at,
            updatedAt: $object->updated_at,
        );
    }
}
