<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Database\Seeders\CategorySeeder;
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

    public function test_category_list_is_created_and_scoped_to_the_authenticated_user(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/categories')
            ->assertOk()
            ->assertJsonCount(6, 'data')
            ->assertJsonPath('data.0.name', '仕事');

        $categoryId = $response->json('data.0.id');

        $this->assertDatabaseHas('categories', [
            'id' => $categoryId,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseMissing('categories', [
            'id' => $categoryId,
            'user_id' => $otherUser->id,
        ]);
    }

    public function test_category_seeder_creates_default_categories_for_each_user(): void
    {
        $users = User::factory()->count(2)->create();

        $this->seed(CategorySeeder::class);

        foreach ($users as $user) {
            $this->assertDatabaseCount('categories', 12);
            $this->assertDatabaseHas('categories', [
                'user_id' => $user->id,
                'name' => '仕事',
            ]);
        }
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
