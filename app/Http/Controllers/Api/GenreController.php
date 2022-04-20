<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use Costa\Core\UseCases\Genre\ListGenreUseCase;
use App\Http\Resources\GenreResource as Resource;
use Costa\Core\UseCases\Genre\CreateGenreUseCase;
use Costa\Core\UseCases\Genre\DeleteGenreUseCase;
use Costa\Core\UseCases\Genre\DTO\List\Input as ListInput;
use Costa\Core\UseCases\Genre\DTO\Created\Input as CreateInput;
use Costa\Core\UseCases\Genre\DTO\Find\Input as FindInput;
use Costa\Core\UseCases\Genre\DTO\Updated\Input as UpdateInput;
use Costa\Core\UseCases\Genre\DTO\Deleted\Input as DeleteInput;
use Costa\Core\UseCases\Genre\GetGenreUseCase;
use Costa\Core\UseCases\Genre\UpdateGenreUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    public function index(ListGenreUseCase $useCase, Request $request)
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

    public function show(string $id, GetGenreUseCase $useCase)
    {
        $response = $useCase->execute(new FindInput($id));
        return (new Resource($response))->response();
    }

    public function store(StoreGenreRequest $request, CreateGenreUseCase $createCategoryUseCase)
    {
        $response = $createCategoryUseCase->execute(
            input: new CreateInput(
                name: $request->name,
                isActive: (bool) $request->is_active ?? true
            )
        );

        return (new Resource($response))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(string $id, UpdateGenreUseCase $useCase, UpdateGenreRequest $request)
    {
        $response = $useCase->execute(
            input: new UpdateInput(
                id: $id,
                name: $request->name,
                isActive: $request->is_active
            )
        );

        return (new Resource($response))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(string $id, DeleteGenreUseCase $useCase)
    {
        $useCase->execute(
            input: new DeleteInput(
                id: $id,
            )
        );

        return response()->noContent();
    }
}
