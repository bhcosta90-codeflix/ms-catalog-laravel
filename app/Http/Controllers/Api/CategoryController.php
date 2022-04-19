<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Costa\Core\UseCases\Category\CreateCategoryUseCase;
use Costa\Core\UseCases\Category\DeleteCategoryUseCase;
use Costa\Core\UseCases\Category\ListCategoryUseCase;
use Costa\Core\UseCases\Category\DTO\Category\ListCategory\Input as ListCategoryInput;
use Costa\Core\UseCases\Category\DTO\Category\CreatedCategory\Input as CreatedCategoryInput;
use Costa\Core\UseCases\Category\DTO\Category\FindCategory\Input as FindCategoryInput;
use Costa\Core\UseCases\Category\DTO\Category\UpdatedCategory\Input as UpdatedCategoryInput;
use Costa\Core\UseCases\Category\DTO\Category\DeletedCategory\Input as DeletedCategoryInput;
use Costa\Core\UseCases\Category\GetCategoryUseCase;
use Costa\Core\UseCases\Category\UpdateCategoryUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoryUseCase $listCategoryUseCase)
    {
        $response = $listCategoryUseCase->execute(
            input: new ListCategoryInput(
                filter: $request->all(),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total', 15)
            )
        );

        return CategoryResource::collection(collect($response->items))
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
            input: new CreatedCategoryInput(
                name: $request->name,
                description: $request->description,
                isActive: (bool) $request->is_active ?? true
            )
        );

        return (new CategoryResource($response))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(string $id, GetCategoryUseCase $useCase)
    {
        $response = $useCase->execute(new FindCategoryInput($id));
        return (new CategoryResource($response))->response();
    }

    public function update(string $id, UpdateCategoryUseCase $useCase, UpdateCategoryRequest $request)
    {
        $response = $useCase->execute(
            input: new UpdatedCategoryInput(
                id: $id,
                name: $request->name,
                description: $request->description,
                isActive: $request->is_active
            )
        );

        return (new CategoryResource($response))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(string $id, DeleteCategoryUseCase $useCase)
    {
        $useCase->execute(
            input: new DeletedCategoryInput(
                id: $id,
            )
        );

        return response()->noContent();
    }
}
