<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Presenters\Paginator;
use Costa\Core\Modules\Genre\Entities\Genre as Entity;
use Costa\Core\Utils\Domains\Exceptions\NotFoundDomainException;
use Costa\Core\Modules\Genre\Repositories\GenreRepositoryInterface;
use Costa\Core\Utils\Domains\Repositories\PaginationInterface;
use App\Models\Genre as Model;
use Costa\Core\Utils\Domains\ValueObject\Uuid;

class GenreRepository implements GenreRepositoryInterface
{
    public function __construct(private Model $model)
    {
        //
    }

    public function getIds(array $id = []): array
    {
        return $this->model->whereIn('id', $id)->pluck('id')->toArray();
    }

    public function insert(Entity $entity): Entity
    {
        $obj = $this->model->create([
            'id' => $entity->id,
            'name' => $entity->name,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt()
        ]);

        if (count($entity->categories ?: [])) {
            $obj->categories()->sync($entity->categories);
        }

        return $this->toEntity($obj);
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
        ?array $filters = null,
        ?string $orderColumn = null,
        ?string $order = null,
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {
        $data = $this->model->paginate(
            perPage: $totalPage,
            page: $page
        );
        return new Paginator($data);
    }

    public function update(Entity $entity): Entity
    {
        if ($model = $this->findByDb($entity->id())) {

            $model->update([
                'name' => $entity->name,
                'is_active' => $entity->isActive
            ]);

            $model->categories()->sync($entity->categories);

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
            id: new Uuid($object->id),
            name: $object->name,
            isActive: $object->is_active,
            categories: $object->categories()->pluck('id')->toArray()
        );
    }

    protected function findByDb(string $id)
    {
        if ($model = $this->model->find($id)) {
            return $model;
        }

        throw new NotFoundDomainException(__('Genre not found'));
    }
}
