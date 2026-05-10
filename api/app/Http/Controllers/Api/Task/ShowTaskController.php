<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;

class ShowTaskController extends Controller
{
    public function __invoke(Task $task)
    {
        $task->load('checklists');

        return response()->json([
            'id' => $task->id,
            'user_id' => $task->user_id,
            'category_id' => $task->category_id,
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
