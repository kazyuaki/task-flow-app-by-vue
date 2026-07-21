<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 現在のパスワードが正しい場合にパスワードを更新できること
     */
    public function test_update_password_succeeds_when_current_password_is_correct(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password'),
        ]);

        $this->actingAs($user)
            ->putJson('/api/profile/password', [
                'current_password' => 'current-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'パスワードを更新しました。');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    /**
     * 現在のパスワードが誤っている場合に更新できないこと
     */
    public function test_update_password_fails_when_current_password_is_incorrect(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password'),
        ]);

        $this->actingAs($user)
            ->putJson('/api/profile/password', [
                'current_password' => 'incorrect-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['current_password']);

        $this->assertTrue(Hash::check('current-password', $user->fresh()->password));
    }

    /**
     * 確認用パスワードが一致しない場合に更新できないこと
     */
    public function test_update_password_fails_when_confirmation_does_not_match(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password'),
        ]);

        $this->actingAs($user)
            ->putJson('/api/profile/password', [
                'current_password' => 'current-password',
                'password' => 'new-password',
                'password_confirmation' => 'different-password',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * ゲストユーザーがパスワードを更新できないこと
     */
    public function test_guest_cannot_update_password(): void
    {
        $this->putJson('/api/profile/password', [
            'current_password' => 'current-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertUnauthorized();
    }
}
