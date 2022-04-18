<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Presenters\Paginator;
use Costa\Core\Domains\Entities\Category as Entity;
use Costa\Core\Domains\Exceptions\NotFoundDomainException;
use Costa\Core\Domains\Repositories\CategoryRepositoryInterface;
use Costa\Core\Domains\Repositories\PaginationInterface;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private Model $model)
    {
        //
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
        if ($model = $this->model->find($id)) {
            return $this->toEntity($model);
        }

        throw new NotFoundDomainException();
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
        return new Entity(
            name: 'dkawopdka'
        );
    }

    public function delete(Entity $entity): bool
    {
        //
    }

    public function toEntity(object $object): Entity
    {
        return new Entity(
            id: $object->id,
            name: $object->name,
        );
    }
}
