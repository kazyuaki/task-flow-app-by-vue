<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーがカテゴリを作成できること
     */
    public function test_create_category_saves_category_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/categories', ['name' => '買い物'])
            ->assertCreated()
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.name', '買い物')
            ->assertJsonPath('data.color', '#868e96');

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => '買い物',
            'color' => '#868e96',
        ]);
    }

    /**
     * 同じユーザーが同名カテゴリを作成した場合にバリデーションエラーとなること
     */
    public function test_create_category_fails_when_name_is_duplicated_for_same_user(): void
    {
        $user = User::factory()->create();
        Category::factory()->for($user)->create(['name' => '買い物']);

        $this->actingAs($user)
            ->postJson('/api/categories', ['name' => '買い物'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * 他ユーザーと同名のカテゴリを作成できること
     */
    public function test_create_category_allows_name_used_by_another_user(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();
        Category::factory()->for($otherUser)->create(['name' => '買い物']);

        $this->actingAs($user)
            ->postJson('/api/categories', ['name' => '買い物'])
            ->assertCreated();

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => '買い物',
        ]);
    }

    /**
     * カテゴリ名が不足している場合にバリデーションエラーとなること
     */
    public function test_create_category_fails_validation_when_name_is_missing(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/categories', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
