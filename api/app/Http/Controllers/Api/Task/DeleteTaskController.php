<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeleteTaskController extends Controller
{
    /**
     * タスクを削除する
     */
    public function __invoke(Request $request, Task $task): JsonResponse
    {
        abort_unless($task->user_id === $request->user()->id, 404);

        $task->delete();

        return response()->json([
            'message' => 'タスクを削除しました',
        ]);
    }
}
