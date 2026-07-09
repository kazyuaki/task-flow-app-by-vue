<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CreateCategoryController extends Controller
{
    public function __invoke(
        CreateCategoryRequest $request,
    ): JsonResponse {
        $category = Category::create([
            'user_id' => $request->user()->id,
            'name' => $request->validated('name'),
            'color' => '#868e96',
        ]);

        return response()->json([
            'data' => $category,
        ], 201);
    }
}