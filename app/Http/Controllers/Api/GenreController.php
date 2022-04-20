<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Costa\Core\UseCases\Genre\ListGenreUseCase;
use App\Http\Resources\GenreResource as Resource;
use Costa\Core\UseCases\Genre\DTO\List\Input as ListInput;
use Costa\Core\UseCases\Genre\DTO\Created\Input as CreateInput;
use Costa\Core\UseCases\Genre\DTO\Find\Input as FindInput;
use Costa\Core\UseCases\Genre\DTO\Updated\Input as UpdateInput;
use Costa\Core\UseCases\Genre\DTO\Deleted\Input as DeleteInput;

use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(ListGenreUseCase $useCase, Request $request){
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
}
