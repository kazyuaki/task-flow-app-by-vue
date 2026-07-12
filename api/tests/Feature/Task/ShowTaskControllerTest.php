<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーが自身のタスク詳細を取得できること
     */
    public function test_show_returns_task_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'category_id' => null,
            'title' => '自分のタスク',
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->toDateString(),
        ]);

        $this->actingAs($user)
            ->getJson("/api/tasks/{$task->id}")
            ->assertOk()
            ->assertJsonPath('id', $task->id)
            ->assertJsonPath('title', '自分のタスク');
    }

    /**
     * 他ユーザーのタスク詳細取得で 404 が返ること
     */
    public function test_show_returns_not_found_for_other_users_task(): void
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
            ->getJson("/api/tasks/{$task->id}")
            ->assertNotFound();
    }
}
