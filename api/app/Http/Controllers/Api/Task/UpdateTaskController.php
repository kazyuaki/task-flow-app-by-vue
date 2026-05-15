<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class UpdateTaskController extends Controller
{
    public function __invoke(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $task) {

            // タスクの更新
            $taskData = collect($validated)
                ->except('checklist')
                ->toArray();

            $task->update($taskData);

            $checklists = $validated['checklist'] ?? [];

            $sentIds = collect($checklists)
                ->pluck('id')
                ->filter()
                ->toArray();

            $task->checklists()
                ->whereNotIn('id', $sentIds)
                ->delete();

            foreach ($checklists as $checklist) {
                $task->checklists()->updateOrCreate(
                    [
                        'id' => $checklist['id'] ?? null,
                    ],
                    [
                        'label' => $checklist['label'],
                        'done' => $checklist['done'] ?? false,
                        'sort_order' => $checklist['sort_order'] ?? 0,
                    ]
                );
            }
        });

        return response()->json([
            'message' => 'タスクが更新されました',
            'task' => $task->fresh(),
        ]);
    }
}