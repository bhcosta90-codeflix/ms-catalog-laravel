<?php

namespace Tests\Feature\UseCases\Category;

use App\Repositories\Eloquent\CategoryRepository as Repository;
use App\Models\Category as Model;
use Costa\Core\UseCases\Category\DTO\Find\Input;
use Costa\Core\UseCases\Category\GetCategoryUseCase as UseCase;
use Tests\TestCase;

class GetCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGet()
    {
        $category = Model::factory()->create();
        $repo = new Repository(new Model);

        $useCase = new UseCase($repo);
        $response = $useCase->execute(new Input($category->id));

        $this->assertEquals($category->id, $response->id);
        $this->assertEquals($category->name, $response->name);
        $this->assertEquals($category->description, $response->description);
        $this->assertEquals($category->is_active, $response->isActive);
    }
}
