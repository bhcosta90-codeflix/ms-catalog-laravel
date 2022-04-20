<?php

namespace Tests\Feature\UseCases\Category;

use App\Repositories\Eloquent\CategoryRepository as Repository;
use App\Models\Category as Model;
use Costa\Core\UseCases\Category\DeleteCategoryUseCase as UseCase;
use Costa\Core\UseCases\Category\DTO\Deleted\Input;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDeleted()
    {
        $category = Model::factory()->create();

        $repo = new Repository(new Model);

        $useCase = new UseCase($repo);
        $useCase->execute(new Input($category->id));

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
            'deleted_at' => null
        ]);
    }
}
