<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tasks = [
            [
                'title' => 'ログインAPIを実装する',
                'description' => 'メールアドレスとパスワードで認証し、ログイン結果を返す処理を作成する。',
            ],
            [
                'title' => 'タスク一覧画面を調整する',
                'description' => '検索、絞り込み、ステータス表示が見やすくなるようにレイアウトを整える。',
            ],
            [
                'title' => 'カテゴリ登録処理を追加する',
                'description' => 'タスクに紐づけるカテゴリを登録できるように、APIと画面の入力項目を用意する。',
            ],
            [
                'title' => '期限切れタスクの表示を確認する',
                'description' => '期限を過ぎたタスクが一覧画面で赤色のラベルとして表示されるか確認する。',
            ],
            [
                'title' => '新規作成フォームの入力チェック',
                'description' => 'タイトル、説明、期限の必須チェックと、エラー表示の動きを確認する。',
            ],
            [
                'title' => 'READMEのセットアップ手順を更新する',
                'description' => 'Docker起動、マイグレーション、シーディングまでの手順を整理して追記する。',
            ],
            [
                'title' => 'タスク詳細ページの導線を確認する',
                'description' => '一覧から詳細へ移動し、戻るリンクで一覧画面へ戻れることを確認する。',
            ],
            [
                'title' => 'APIレスポンス形式を整理する',
                'description' => 'フロント側で扱いやすいように、タスクとカテゴリのレスポンス項目を見直す。',
            ],
            [
                'title' => 'スマホ表示の崩れを修正する',
                'description' => '横幅が狭い画面で検索フォームやテーブルが見切れないように調整する。',
            ],
            [
                'title' => '完了タスクの見た目を調整する',
                'description' => '完了済みのタスクが他のステータスと区別しやすいように色や表示ルールを整える。',
            ],
        ];

        $task = fake()->randomElement($tasks);

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'title' => $task['title'],
            'description' => $task['description'],
            'status' => fake()->randomElement([
                Task::STATUS_PENDING,
                Task::STATUS_IN_PROGRESS,
                Task::STATUS_COMPLETED,
            ]),
            'priority' => fake()->randomElement([
                Task::PRIORITY_LOW,
                Task::PRIORITY_MEDIUM,
                Task::PRIORITY_HIGH,
            ]),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
