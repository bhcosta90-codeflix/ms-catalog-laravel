<?php

namespace Tests\Feature\UseCases\Genre;

use App\Repositories\Eloquent\GenreRepository as Repository;
use App\Models\Genre as Model;
use Costa\Core\UseCases\Genre\DeleteGenreUseCase as UseCase;
use Costa\Core\UseCases\Genre\DTO\Deleted\Input;
use Tests\TestCase;

class DeleteGenreUseCaseTest extends TestCase
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
