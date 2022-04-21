<?php

namespace Tests\Feature\UseCases\Category;

use App\Repositories\Eloquent\CategoryRepository as Repository;
use App\Models\Category as Model;
use Costa\Core\Modules\Category\UseCases\UpdateCategoryUseCase as UseCase;
use Costa\Core\Modules\Category\UseCases\DTO\Updated\Input;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function testUpdate()
    {
        $category = Model::factory()->create();
        $repo = new Repository(new Model);

        $useCase = new UseCase($repo);
        $response = $useCase->execute(new Input(
            id: $category->id,
            name: 'teste'
        ));

        $this->assertDatabaseHas('categories', [
            'id' => $response->id,
            'name' => 'teste',
            'description' => $category->description
        ]);

    }
}
