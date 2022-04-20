<?php

namespace Tests\Feature\UseCases\Genre;

use App\Models\Category;
use App\Repositories\Eloquent\GenreRepository as Repository;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Transactions\TransactionDatabase;
use Costa\Core\UseCases\Genre\UpdateGenreUseCase as UseCase;
use Costa\Core\UseCases\Genre\DTO\Updated\Input;
use Tests\TestCase;

class UpdateGenreUseCaseTest extends TestCase
{
    public function testUpdate()
    {
        $category = Model::factory()->create();
        $repo = new Repository(new Model);

        $useCase = new UseCase(
            repository: $repo,
            transactionContract: new TransactionDatabase(),
            categoryRepositoryInterface: new CategoryRepository(new Category())
        );
        $response = $useCase->execute(new Input(
            id: $category->id,
            name: 'teste'
        ));

        $this->assertDatabaseHas('genres', [
            'id' => $response->id,
            'name' => 'teste',
        ]);

    }
}
