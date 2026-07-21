<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCategoryListControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーのカテゴリ一覧が自身のカテゴリのみを返すこと
     */
    public function test_list_returns_only_authenticated_users_categories(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();
        $ownCategory = Category::factory()->for($user)->create([
            'name' => '個人カテゴリ',
        ]);
        Category::factory()->for($otherUser)->create([
            'name' => '他人のカテゴリ',
        ]);

        $this->actingAs($user)
            ->getJson('/api/categories')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $ownCategory->id,
                'name' => '個人カテゴリ',
            ])
            ->assertJsonMissing([
                'name' => '他人のカテゴリ',
            ]);
    }

    /**
     * カテゴリ一覧取得時にデフォルトカテゴリが作成されること
     */
    public function test_list_creates_default_categories_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/categories')
            ->assertOk()
            ->assertJsonCount(6, 'data')
            ->assertJsonPath('data.0.name', '仕事')
            ->assertJsonPath('data.5.name', 'その他');

        $this->assertDatabaseCount('categories', 6);
        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => '仕事',
            'color' => '#1c7ed6',
        ]);
    }

    /**
     * ゲストユーザーがカテゴリ一覧にアクセスできないこと
     */
    public function test_guest_cannot_access_category_list(): void
    {
        $this->getJson('/api/categories')
            ->assertUnauthorized();
    }
}
