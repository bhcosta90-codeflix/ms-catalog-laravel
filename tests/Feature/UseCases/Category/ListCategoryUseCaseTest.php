<?php

namespace Tests\Feature\UseCases\Category;

use App\Repositories\Eloquent\CategoryRepository as Repository;
use App\Models\Category as Model;
use Costa\Core\Modules\Category\UseCases\ListCategoryUseCase as UseCase;
use Costa\Core\Modules\Category\UseCases\DTO\List\Input;
use Tests\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    public function testList()
    {
        Model::factory(35)->create();
        $useCase = $this->createUseCase();
        $this->assertCount(15, $useCase->items);
        $this->assertEquals(35, $useCase->total);
        $this->assertEquals(3, $useCase->last_page);
    }

    public function testListEmpty()
    {
        $this->assertCount(0, $this->createUseCase()->items);
    }

    private function createUseCase()
    {
        $repo = new Repository(new Model);
        $useCase = new UseCase($repo);
        $response = $useCase->execute(new Input());

        return $response;
    }
}
