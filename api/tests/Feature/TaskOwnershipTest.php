<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_only_returns_the_authenticated_users_tasks(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();
        $ownTask = $this->createTask($user, '自分のタスク');
        $this->createTask($otherUser, '他人のタスク');

        $this->actingAs($user)
            ->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownTask->id);
    }

    public function test_created_task_always_belongs_to_the_authenticated_user(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();

        $this->actingAs($user)
            ->postJson('/api/tasks', [
                'user_id' => $otherUser->id,
                'title' => '新しいタスク',
                'description' => '説明',
                'status' => Task::STATUS_PENDING,
                'priority' => Task::PRIORITY_MEDIUM,
                'due_date' => now()->toDateString(),
            ])
            ->assertCreated()
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => '新しいタスク',
        ]);
    }

    public function test_another_users_task_cannot_be_shown_updated_or_deleted(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();
        $task = $this->createTask($otherUser, '他人のタスク');

        $this->actingAs($user)->getJson("/api/tasks/{$task->id}")->assertNotFound();

        $this->actingAs($user)
            ->putJson("/api/tasks/{$task->id}", [
                'title' => '書き換え',
                'description' => '説明',
                'status' => Task::STATUS_COMPLETED,
                'priority' => Task::PRIORITY_HIGH,
                'due_date' => now()->toDateString(),
            ])
            ->assertNotFound();

        $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}")->assertNotFound();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => '他人のタスク',
            'deleted_at' => null,
        ]);
    }

    private function createTask(User $user, string $title): Task
    {
        return Task::create([
            'user_id' => $user->id,
            'category_id' => null,
            'title' => $title,
            'description' => '説明',
            'status' => Task::STATUS_PENDING,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => now()->addDay(),
        ]);
    }
}
