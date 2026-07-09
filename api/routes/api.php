<?php

use App\Http\Controllers\Api\Category\CreateCategoryController;
use App\Http\Controllers\Api\Category\GetCategoryListController;
use App\Http\Controllers\Api\Task\DeleteTaskController;
use App\Http\Controllers\Api\Task\GetTaskListController;
use App\Http\Controllers\Api\Task\ShowTaskController;
use App\Http\Controllers\Api\Task\StoreTaskController;
use App\Http\Controllers\Api\Task\UpdateTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // 認証ユーザー情報取得
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // カテゴリ一覧取得
    Route::get('/categories', GetCategoryListController::class);

    // カテゴリ作成
    Route::post('/categories', CreateCategoryController::class);

    // タスク一覧取得
    Route::get('/tasks', GetTaskListController::class);

    // タスク作成
    Route::post('/tasks', StoreTaskController::class);

    // タスク詳細取得
    Route::get('/tasks/{task}', ShowTaskController::class);

    // タスク更新
    Route::put('/tasks/{task}', UpdateTaskController::class);

    // タスク削除
    Route::delete('/tasks/{task}', DeleteTaskController::class);

});
