<?php

namespace Tests\Feature\UseCases\Genre;

use App\Models\Category;
use App\Repositories\Eloquent\GenreRepository as Repository;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Transactions\TransactionDatabase;
use Costa\Core\Utils\Exceptions\NotFoundDomainException;
use Costa\Core\Utils\ValueObject\Uuid;
use Costa\Core\Modules\Genre\UseCases\CreateGenreUseCase as UseCase;
use Costa\Core\Modules\Genre\UseCases\DTO\Created\Input;
use Costa\Core\Utils\Contracts\TransactionInterface;
use Exception;
use Mockery;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $categories = Category::factory(4)->create()->pluck('id')->toArray();

        $repo = new Repository(new Model);

        $useCase = new UseCase(
            repository: $repo,
            transactionInterface: new TransactionDatabase(),
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
        $this->expectException(NotFoundDomainException::class);
        $this->expectExceptionMessage("Category fake-id not found");

        $repo = new Repository(new Model);

        $useCase = new UseCase(
            repository: $repo,
            transactionInterface: new TransactionDatabase(),
            categoryRepositoryInterface: new CategoryRepository(new Category())
        );

        $useCase->execute(new Input(
            name: 'teste',
            categories: ['fake-id']
        ));
    }

    public function testCreateWithRollback()
    {
        $categories = Category::factory(4)->create()->pluck('id')->toArray();

        $repo = Mockery::mock(Repository::class, [
            new Model
        ]);
        $repo->shouldReceive('insert')->andThrow(Exception::class);

        $useCase = new UseCase(
            repository: $repo,
            transactionInterface: new TransactionDatabase(),
            categoryRepositoryInterface: new CategoryRepository(new Category())
        );

        try {
            $useCase->execute(new Input(
                name: 'teste',
                categories: $categories
            ));
        } catch(Exception $e) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }
    }
}
