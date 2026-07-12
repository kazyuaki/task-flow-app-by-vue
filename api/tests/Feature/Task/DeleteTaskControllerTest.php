<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーが自分のタスクを削除できること
     */
    public function test_delete_task_succeeds_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'category_id' => null,
            'title' => '削除対象タスク',
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->toDateString(),
        ]);

        $this->actingAs($user)
            ->deleteJson("/api/tasks/{$task->id}")
            ->assertOk()
            ->assertJsonPath('message', 'タスクを削除しました');

        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * 他ユーザーのタスクを削除しようとした場合に 404 が返り、タスクが削除されないこと
     */
    public function test_delete_task_returns_not_found_for_other_users_task(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();
        $task = Task::create([
            'user_id' => $otherUser->id,
            'category_id' => null,
            'title' => '他人のタスク',
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->toDateString(),
        ]);

        $this->actingAs($user)
            ->deleteJson("/api/tasks/{$task->id}")
            ->assertNotFound();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => null,
        ]);
    }
}
