<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskChecklist;
use Illuminate\Database\Seeder;

class TaskChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::query()
            ->orderBy('id')
            ->get()
            ->each(function (Task $task): void {
                $items = [
                    ['label' => '要件を確認する', 'done' => true],
                    ['label' => '実装方針を整理する', 'done' => $task->status !== Task::STATUS_PENDING],
                    ['label' => '動作確認をする', 'done' => $task->status === Task::STATUS_COMPLETED],
                ];

                foreach ($items as $index => $item) {
                    TaskChecklist::query()->updateOrCreate(
                        [
                            'task_id' => $task->id,
                            'label' => $item['label'],
                        ],
                        [
                            'done' => $item['done'],
                            'sort_order' => $index + 1,
                        ],
                    );
                }
            });
    }
}
