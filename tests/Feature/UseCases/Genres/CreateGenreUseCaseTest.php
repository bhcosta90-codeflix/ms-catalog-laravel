<?php

namespace Tests\Feature\UseCases\Genres;

use App\Models\Category;
use App\Repositories\Eloquent\GenreRepository as Repository;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Transactions\TransactionDatabase;
use Costa\Core\UseCases\Genre\CreateGenreUseCase as UseCase;
use Costa\Core\UseCases\Genre\DTO\Created\Input;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create()
    {
        $repo = new Repository(new Model);

        $useCase = new UseCase(
            repository: $repo,
            transactionContract: new TransactionDatabase(),
            categoryRepositoryInterface: new CategoryRepository(new Category())
        );

        $response = $useCase->execute(new Input(
            name: 'teste'
        ));

        $this->assertEquals('teste', $response->name);
        $this->assertNotEmpty($response->id);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id,
            'name' => $response->name,
        ]);
    }
}
