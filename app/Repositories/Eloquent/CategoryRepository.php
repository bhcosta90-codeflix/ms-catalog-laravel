<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Presenters\Paginator;
use Costa\Core\Domains\Entities\Category as Entity;
use Costa\Core\Domains\Exceptions\NotFoundDomainException;
use Costa\Core\Domains\Repositories\CategoryRepositoryInterface;
use Costa\Core\Domains\Repositories\PaginationInterface;
use App\Models\Category as Model;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private Model $model)
    {
        //
    }

    public function getIds(array $id): array
    {
        return $this->model->whereIn('id', $id)->get()->toArray();
    }

    public function insert(Entity $entity): Entity
    {
        $category = $this->model->create([
            'id' => $entity->id(),
            'name' => $entity->name,
            'description' => $entity->description,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt(),
        ]);

        return $this->toEntity($category);
    }

    public function findById(string $id): Entity
    {
        if ($model = $this->findByDb($id)) {
            return $this->toEntity($model);
        }
    }

    public function findAll(array $filters = [], ?string $orderColumn = null, ?string $order = null): array
    {
        $data = $this->model->get();
        return $data->toArray();
    }

    public function paginate(
        array $filters = [],
        ?string $orderColumn = null,
        ?string $order = null,
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {
        $data = $this->model->paginate($totalPage);
        return new Paginator($data);
    }

    public function update(Entity $entity): Entity
    {
        if ($model = $this->findByDb($entity->id())) {
            $model->update([
                'name' => $entity->name,
                'description' => $entity->description,
                'is_active' => $entity->isActive
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

    public function toEntity(object $object): Entity
    {
        return new Entity(
            id: $object->id,
            name: $object->name,
            description: $object->description,
            isActive: $object->is_active,
        );
    }

    protected function findByDb(string $id)
    {
        if ($model = $this->model->find($id)) {
            return $model;
        }

        throw new NotFoundDomainException(__('Category not found'));
    }
}
