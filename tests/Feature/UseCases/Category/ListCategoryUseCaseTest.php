<?php

namespace Tests\Feature\UseCases\Category;

use App\Repositories\Eloquent\CategoryRepository as Repository;
use App\Models\Category as Model;
use Costa\Core\UseCases\Category\ListCategoryUseCase;
use Costa\Core\UseCases\Category\DTO\Category\ListCategory\Input;
use Tests\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    public function test_list()
    {
        Model::factory(35)->create();
        $this->assertCount(15, $this->createUseCase()->items);
        $this->assertEquals(35, $this->createUseCase()->total);
        $this->assertEquals(3, $this->createUseCase()->last_page);
    }

    public function test_list_empty()
    {
        $this->assertCount(0, $this->createUseCase()->items);
    }

    private function createUseCase()
    {
        $repo = new Repository(new Model);
        $useCase = new ListCategoryUseCase($repo);
        $response = $useCase->execute(new Input());

        return $response;
    }
}
