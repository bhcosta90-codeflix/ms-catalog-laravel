<?php

namespace Tests\Feature\UseCases\Genre;

use App\Repositories\Eloquent\GenreRepository as Repository;
use App\Models\Genre as Model;
use Costa\Core\Modules\Genre\UseCases\ListGenreUseCase as UseCase;
use Costa\Core\Modules\Genre\UseCases\DTO\List\Input;
use Tests\TestCase;

class ListGenreUseCaseTest extends TestCase
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
