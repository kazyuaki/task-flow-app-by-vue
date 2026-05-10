<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;

class StoreTaskController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $checklist = $validated['checklist'] ?? [];
        unset($validated['checklist']);

        $task = Task::create($request->validated());

        foreach ($checklist as $index => $item) {
            $task->checklists()->create([
                'label' => $item['label'],
                'done' => $item['done'] ?? false,
                'sort_order' => $item['sort_order'] ?? $index + 1,
            ]);
        }

        return response()->json([
            'data' => $task,
        ], 201);
    }
}
