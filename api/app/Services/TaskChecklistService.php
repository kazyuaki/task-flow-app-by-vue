<?php

namespace App\Services;

use App\Models\Task;

class TaskChecklistService
{
    /**
     * チェックリスト新規作成
     *
     * @param Task $task チェックリストを追加するタスク
     * @param array $checklist チェックリストのデータ配列
     */
    public function createMany(Task $task, array $checklist): void
    {
        foreach ($checklist as $index => $item) {
            $task->checklists()->create([
                'label' => $item['label'],
                'done' => $item['done'] ?? false,
                'sort_order' => $item['sort_order'] ?? $index + 1,
            ]);
        }
    }

    /**
     * チェックリスト同期処理
     * 
     * @param Task $task 同期対象のタスク
     * @param array $checklist 同期するチェックリストのデータ配列
     */
    public function sync(Task $task, array $checklists): void
    {
        // 送信されたチェックリストのIDを収集
        $sentIds = collect($checklists)
            ->pluck('id')
            ->filter()
            ->toArray();
        // 送信されたIDに含まれないものを削除
        $task->checklists()
            ->whereNotIn('id', $sentIds)
            ->delete();
        
        // 存在するものは更新、存在しないものは新規作成
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
    }
}