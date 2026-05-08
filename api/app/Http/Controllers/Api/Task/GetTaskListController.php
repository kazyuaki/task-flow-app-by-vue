<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class GetTaskListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $tasks = Task::with(['category'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $tasks,
        ]);
    }
}
