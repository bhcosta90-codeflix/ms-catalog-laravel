<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Costa\Core\Modules\Category\UseCases\CreateCategoryUseCase;
use Costa\Core\Modules\Category\UseCases\DeleteCategoryUseCase;
use Costa\Core\Modules\Category\UseCases\ListCategoryUseCase;
use App\Http\Resources\CategoryResource as Resource;
use Costa\Core\Modules\Category\UseCases\DTO\List\Input as ListInput;
use Costa\Core\Modules\Category\UseCases\DTO\Created\Input as CreateInput;
use Costa\Core\Modules\Category\UseCases\DTO\Find\Input as FindInput;
use Costa\Core\Modules\Category\UseCases\DTO\Updated\Input as UpdateInput;
use Costa\Core\Modules\Category\UseCases\DTO\Deleted\Input as DeleteInput;
use Costa\Core\Modules\Category\UseCases\GetCategoryUseCase;
use Costa\Core\Modules\Category\UseCases\UpdateCategoryUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoryUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListInput(
                filter: $request->all(),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total', 15)
            )
        );

        return Resource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from,
                    'current_page' => $response->current_page
                ]
            ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $createCategoryUseCase)
    {
        $response = $createCategoryUseCase->execute(
            input: new CreateInput(
                name: $request->name,
                description: $request->description,
                isActive: (bool) $request->is_active ?? true
            )
        );

        return (new Resource($response))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(string $id, GetCategoryUseCase $useCase)
    {
        $response = $useCase->execute(new FindInput($id));
        return (new Resource($response))->response();
    }

    public function update(string $id, UpdateCategoryUseCase $useCase, UpdateCategoryRequest $request)
    {
        $response = $useCase->execute(
            input: new UpdateInput(
                id: $id,
                name: $request->name,
                description: $request->description,
                isActive: $request->is_active
            )
        );

        return (new Resource($response))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(string $id, DeleteCategoryUseCase $useCase)
    {
        $useCase->execute(
            input: new DeleteInput(
                id: $id,
            )
        );

        return response()->noContent();
    }
}
