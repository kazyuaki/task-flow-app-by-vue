<?php

use App\Http\Controllers\Api\Task\GetTaskListController;
use Illuminate\Support\Facades\Route;

// 一覧画面
Route::get('/tasks', GetTaskListController::class);