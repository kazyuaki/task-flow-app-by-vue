<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーがプロフィールを更新できること
     */
    public function test_update_profile_succeeds_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'name' => '更新前',
            'email' => 'before@example.com',
        ]);

        $this->actingAs($user)
            ->putJson('/api/profile', [
                'name' => '更新後',
                'email' => 'after@example.com',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'プロフィールを更新しました。')
            ->assertJsonPath('user.name', '更新後')
            ->assertJsonPath('user.email', 'after@example.com');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '更新後',
            'email' => 'after@example.com',
        ]);
    }

    /**
     * 自身のメールアドレスを変更せずにプロフィールを更新できること
     */
    public function test_update_profile_allows_current_email_address(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->actingAs($user)
            ->putJson('/api/profile', [
                'name' => '新しい名前',
                'email' => 'user@example.com',
            ])
            ->assertOk();
    }

    /**
     * 他ユーザーが使用中のメールアドレスでは更新できないこと
     */
    public function test_update_profile_fails_when_email_is_used_by_another_user(): void
    {
        [$user, $otherUser] = User::factory()->count(2)->create();

        $this->actingAs($user)
            ->putJson('/api/profile', [
                'name' => '更新後',
                'email' => $otherUser->email,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    /**
     * ゲストユーザーがプロフィールを更新できないこと
     */
    public function test_guest_cannot_update_profile(): void
    {
        $this->putJson('/api/profile', [
            'name' => '更新後',
            'email' => 'after@example.com',
        ])->assertUnauthorized();
    }
}
