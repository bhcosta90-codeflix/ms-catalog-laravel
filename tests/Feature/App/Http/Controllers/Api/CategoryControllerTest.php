<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController as Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryRepository as Repository;
use Costa\Core\UseCases\Category\{
    ListCategoryUseCase,
    CreateCategoryUseCase,
    DeleteCategoryUseCase,
    GetCategoryUseCase,
    UpdateCategoryUseCase
};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    private Repository $repo;

    private Controller $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new Repository(new Model);
        $this->controller = new Controller;
    }

    public function test_index()
    {
        $request = new Request();
        $useCase = new ListCategoryUseCase($this->repo);

        $response = $this->controller->index($request, $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }

    public function test_created()
    {
        $request = new StoreCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Teste'
        ]));

        $useCase = new CreateCategoryUseCase($this->repo);
        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->status());
    }

    public function test_show()
    {
        $category = Model::factory()->create();

        $useCase = new GetCategoryUseCase($this->repo);

        $response = $this->controller->show(
            id: $category->id,
            useCase: $useCase,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
    }

    public function test_update()
    {
        $category = Model::factory()->create();
        $useCase = new UpdateCategoryUseCase($this->repo);

        $request = new UpdateCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'Teste'
        ]));

        $response = $this->controller->update(
            id: $category->id,
            useCase: $useCase,
            request: $request,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
    }

    public function test_delete()
    {
        $category = Model::factory()->create();
        $useCase = new DeleteCategoryUseCase($this->repo);

        $response = $this->controller->destroy(
            id: $category->id,
            useCase: $useCase,
        );

        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
        $this->assertEquals(204, $response->status());
    }
}
