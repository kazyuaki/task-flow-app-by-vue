<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTaskListControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーのタスク一覧が自身のタスクのみを返すこと
     */
    public function test_list_returns_only_authenticated_users_tasks(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();
        $ownTask = Task::create([
            'user_id' => $user->id,
            'category_id' => null,
            'title' => '自分のタスク',
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->toDateString(),
        ]);
        Task::create([
            'user_id' => $otherUser->id,
            'category_id' => null,
            'title' => '他人のタスク',
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->toDateString(),
        ]);

        $this->actingAs($user)
            ->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownTask->id);
    }

    /**
     * ゲストユーザーがタスク一覧にアクセスできないこと
     */
    public function test_guest_cannot_access_task_list(): void
    {
        $this->getJson('/api/tasks')
            ->assertUnauthorized();
    }
}
