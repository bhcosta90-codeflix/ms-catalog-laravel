<?php

namespace Tests\Feature\UseCases\Category;

use App\Repositories\Eloquent\CategoryRepository as Repository;
use App\Models\Category as Model;
use Costa\Core\UseCases\Category\CreateCategoryUseCase as UseCase;
use Costa\Core\UseCases\Category\DTO\Created\Input;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $repo = new Repository(new Model);

        $useCase = new UseCase($repo);
        $response = $useCase->execute(new Input(
            name: 'teste'
        ));
        $this->assertEquals('teste', $response->name);
        $this->assertNotEmpty($response->id);

        $this->assertDatabaseHas('categories', [
            'id' => $response->id,
        ]);
    }
}
