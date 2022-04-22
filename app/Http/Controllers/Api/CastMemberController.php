<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCastMemberRequest;
use App\Http\Requests\UpdateCastMemberRequest;
use Costa\Core\Modules\CastMember\UseCases\ListCastMemberUseCase;
use Illuminate\Http\Request;

use App\Http\Resources\CastMemberResource as Resource;
use Costa\Core\Modules\CastMember\UseCases\CreateCastMemberUseCase;
use Costa\Core\Modules\CastMember\UseCases\DeleteCastMemberUseCase;
use Costa\Core\Modules\CastMember\UseCases\DTO\List\Input as ListInput;
use Costa\Core\Modules\CastMember\UseCases\DTO\Created\Input as CreateInput;
use Costa\Core\Modules\CastMember\UseCases\DTO\Find\Input as FindInput;
use Costa\Core\Modules\CastMember\UseCases\DTO\Updated\Input as UpdateInput;
use Costa\Core\Modules\CastMember\UseCases\DTO\Deleted\Input as DeleteInput;
use Costa\Core\Modules\CastMember\UseCases\GetCastMemberUseCase;
use Costa\Core\Modules\CastMember\UseCases\UpdateCastMemberUseCase;
use Illuminate\Http\Response;

class CastMemberController extends Controller
{
    public function index(Request $request, ListCastMemberUseCase $useCase)
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

    public function show(string $id, GetCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(new FindInput($id));
        return (new Resource($response))->response();
    }

    public function store(StoreCastMemberRequest $request, CreateCastMemberUseCase $createCategoryUseCase)
    {
        $response = $createCategoryUseCase->execute(
            input: new CreateInput(
                name: $request->name,
                type: $request->type,
            )
        );

        return (new Resource($response))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(string $id, UpdateCastMemberUseCase $useCase, UpdateCastMemberRequest $request)
    {
        $response = $useCase->execute(
            input: new UpdateInput(
                id: $id,
                name: $request->name,
                type: $request->type,
            )
        );

        return (new Resource($response))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(string $id, DeleteCastMemberUseCase $useCase)
    {
        $useCase->execute(
            input: new DeleteInput(
                id: $id,
            )
        );

        return response()->noContent();
    }
}
