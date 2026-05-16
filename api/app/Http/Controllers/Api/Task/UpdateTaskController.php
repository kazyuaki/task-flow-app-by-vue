<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskChecklistService;
use Illuminate\Support\Facades\DB;

class UpdateTaskController extends Controller
{
    
    public function __invoke(
        UpdateTaskRequest $request, 
        Task $task,
        TaskChecklistService $taskChecklistService
    ){
        $validated = $request->validated();

        DB::transaction(function () use (
            $validated, 
            $task, 
            $taskChecklistService
        ) {
            // タスクの更新
            $taskData = collect($validated)
                ->except('checklist')
                ->toArray();

            $task->update($taskData);

            // チェックリストの同期
            $taskChecklistService->sync(
                $task,
                $validated['checklist'] ?? []
            );
        
        });

        return response()->json([
            'message' => 'タスクが更新されました',
            'task' => $task->fresh(),
        ]);
    }
}
