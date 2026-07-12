<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーがタスクを作成できること
     */
    public function test_store_task_saves_task_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => '新しいタスク',
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->toDateString(),
        ];

        $this->actingAs($user)
            ->postJson('/api/tasks', $payload)
            ->assertCreated()
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.title', '新しいタスク');

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => '新しいタスク',
        ]);
    }

    /**
     * タイトルが不足している場合にバリデーションエラーとなること
     */
    public function test_store_task_fails_validation_when_title_is_missing(): void
    {
        $user = User::factory()->create();

        $payload = [
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->toDateString(),
        ];

        $this->actingAs($user)
            ->postJson('/api/tasks', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }
}
