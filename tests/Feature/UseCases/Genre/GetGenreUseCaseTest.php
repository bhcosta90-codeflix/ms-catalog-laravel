<?php

namespace Tests\Feature\UseCases\Genre;

use App\Repositories\Eloquent\GenreRepository as Repository;
use App\Models\Genre as Model;
use Costa\Core\Modules\Genre\UseCases\DTO\Find\Input;
use Costa\Core\Modules\Genre\UseCases\GetGenreUseCase as UseCase;
use Tests\TestCase;

class GetGenreUseCaseTest extends TestCase
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
        $this->assertEquals($category->is_active, $response->is_active);
    }
}
