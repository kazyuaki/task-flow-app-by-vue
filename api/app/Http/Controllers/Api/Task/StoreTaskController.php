<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Services\TaskChecklistService;

class StoreTaskController extends Controller
{
    /**
     * 新しいタスクを保存する
     */
    public function __invoke(StoreTaskRequest $request, TaskChecklistService $taskChecklistService)
    {
        $validated = $request->validated();

        $checklist = $validated['checklist'] ?? [];
        unset($validated['checklist']);

        $task = Task::create($validated);

        $taskChecklistService->createMany($task, $checklist);

        return response()->json([
            'data' => $task->load('checklists'),
        ], 201);
    }
}
