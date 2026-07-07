<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\DefaultCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetCategoryListController extends Controller
{
    public function __invoke(
        Request $request,
        DefaultCategoryService $defaultCategoryService,
    ): JsonResponse {
        $defaultCategoryService->ensureFor($request->user());

        $categories = Category::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('id')
            ->get(['id', 'name', 'color']);

        return response()->json([
            'data' => $categories,
        ]);
    }
}
