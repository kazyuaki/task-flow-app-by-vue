<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class DeleteTaskcontroller extends Controller
{
    public function __invoke(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            "message"=> "タスクを削除しました",
        ]);
    }
}