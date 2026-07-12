<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーが自身のタスクを更新できること
     */
    public function test_update_task_succeeds_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'category_id' => null,
            'title' => '古いタイトル',
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->toDateString(),
        ]);

        $payload = [
            'title' => '更新後タイトル',
            'description' => '更新後説明',
            'status' => Task::STATUS_COMPLETED,
            'priority' => Task::PRIORITY_HIGH,
            'due_date' => now()->addDay()->toDateString(),
        ];

        $this->actingAs($user)
            ->putJson("/api/tasks/{$task->id}", $payload)
            ->assertOk()
            ->assertJsonPath('task.title', '更新後タイトル');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => '更新後タイトル',
        ]);
    }

    /**
     * 他ユーザーのタスクを更新しようとした場合に 404 が返ること
     */
    public function test_update_task_returns_not_found_for_other_users_task(): void
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

        $payload = [
            'title' => '悪意の更新',
            'description' => '説明',
            'status' => Task::STATUS_COMPLETED,
            'priority' => Task::PRIORITY_HIGH,
            'due_date' => now()->addDay()->toDateString(),
        ];

        $this->actingAs($user)
            ->putJson("/api/tasks/{$task->id}", $payload)
            ->assertNotFound();
    }
}
