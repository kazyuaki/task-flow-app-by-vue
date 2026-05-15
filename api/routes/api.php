<?php

use App\Http\Controllers\Api\Task\DeleteTaskController;
use App\Http\Controllers\Api\Task\GetTaskListController;
use App\Http\Controllers\Api\Task\ShowTaskController;
use App\Http\Controllers\Api\Task\StoreTaskController;
use App\Http\Controllers\Api\Task\UpdateTaskController;
use Illuminate\Support\Facades\Route;

// 一覧画面
Route::get('/tasks', GetTaskListController::class);

// 新規作成画面 登録処理
Route::post('/tasks', StoreTaskController::class);

// 詳細画面
Route::get('/tasks/{task}', ShowTaskController::class);

// 更新処理
Route::put('/tasks/{task}', UpdateTaskController::class);

// 削除処理
Route::delete('/tasks/{task}', DeleteTaskController::class);
