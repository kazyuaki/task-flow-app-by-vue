<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;

class ShowTaskController extends Controller
{
    public function __invoke(Task $task)
    {
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
        ]);
    }
}
