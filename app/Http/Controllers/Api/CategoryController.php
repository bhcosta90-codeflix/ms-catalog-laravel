<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Costa\Core\UseCases\Category\ListCategoryUseCase;
use Costa\Core\UseCases\Category\DTO\Category\ListCategory\Input;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoryUseCase $listCategoryUseCase)
    {
        $response = $listCategoryUseCase->execute(
            obj: new Input(
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
                    'from' => $response->from
                ]
            ]);
    }
}
