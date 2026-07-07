<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class GetTaskListController extends Controller
{
    /**
     * タスクの一覧を取得する
     */
    public function __invoke(Request $request)
    {
        $tasks = Task::with(['category'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $tasks,
        ]);
    }
}
