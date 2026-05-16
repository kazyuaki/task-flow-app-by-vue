<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class DeleteTaskController extends Controller
{
    /**
     * タスクを削除する
     * @param Task $task
     * @return JsonResponse
     */
    public function __invoke(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            "message"=> "タスクを削除しました",
        ]);
    }
}
