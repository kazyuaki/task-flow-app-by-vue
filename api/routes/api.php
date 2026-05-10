<?php

use App\Http\Controllers\Api\Task\GetTaskListController;
use App\Http\Controllers\Api\Task\ShowTaskController;
use App\Http\Controllers\Api\Task\StoreTaskController;
use Illuminate\Support\Facades\Route;

// 一覧画面
Route::get('/tasks', GetTaskListController::class);

// 新規作成画面 登録処理
Route::post('/tasks', StoreTaskController::class);

Route::get('/tasks/{task}', ShowTaskController::class);
