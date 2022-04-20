<?php

namespace Tests\Feature\UseCases\Genres;

use App\Models\Category;
use App\Repositories\Eloquent\GenreRepository as Repository;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Transactions\TransactionDatabase;
use Costa\Core\Domains\Exceptions\NotFoundDomainException;
use Costa\Core\Domains\ValueObject\Uuid;
use Costa\Core\UseCases\Genre\CreateGenreUseCase as UseCase;
use Costa\Core\UseCases\Genre\DTO\Created\Input;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $categories = Category::factory(4)->create()->pluck('id')->toArray();

        $repo = new Repository(new Model);

        $useCase = new UseCase(
            repository: $repo,
            transactionContract: new TransactionDatabase(),
            categoryRepositoryInterface: new CategoryRepository(new Category())
        );

        $response = $useCase->execute(new Input(
            name: 'teste',
            categories: $categories
        ));

        $this->assertEquals('teste', $response->name);
        $this->assertNotEmpty($response->id);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id,
            'name' => $response->name,
        ]);

        $this->assertDatabaseCount('category_genre', 4);
    }

    public function testCreateCategoriesInvalids()
    {
        $id = (string) Uuid::random();

        $this->expectException(NotFoundDomainException::class);
        $this->expectExceptionMessage("Category {$id} not found");

        $repo = new Repository(new Model);

        $useCase = new UseCase(
            repository: $repo,
            transactionContract: new TransactionDatabase(),
            categoryRepositoryInterface: new CategoryRepository(new Category())
        );

        $useCase->execute(new Input(
            name: 'teste',
            categories: [$id]
        ));
    }
}
