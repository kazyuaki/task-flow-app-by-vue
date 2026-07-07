<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowTaskController extends Controller
{
    /**
     * タスク詳細情報を取得する
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request, Task $task)
    {
        abort_unless($task->user_id === $request->user()->id, 404);

        $task->load(['category', 'checklists']);

        return response()->json([
            'id' => $task->id,
            'user_id' => $task->user_id,
            'category_id' => $task->category_id,
            'category' => $task->category ? [
                'id' => $task->category->id,
                'name' => $task->category->name,
                'color' => $task->category->color,
            ] : null,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
            'priority' => $task->priority,
            'due_date' => $task->due_date,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'checklist' => $task->checklists->map(fn ($item) => [
                'id' => $item->id,
                'label' => $item->label,
                'done' => $item->done,
            ])->values(),
        ]);
    }
}
