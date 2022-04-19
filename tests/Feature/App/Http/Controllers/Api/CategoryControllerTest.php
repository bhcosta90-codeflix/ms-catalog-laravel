<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController as Controller;
use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryRepository as Repository;
use Costa\Core\UseCases\Category\ListCategoryUseCase as UseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    private Repository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new Repository(new Model);
    }
    
    public function test_index()
    {
        $request = new Request();
        $list = new UseCase($this->repo);

        $controller = new Controller;
        $response = $controller->index($request, $list);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }
}
