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
                'title' => 'Laravelの学習を進める',
                'description' => 'Eloquentとリレーションについて学習し、サンプルアプリで動作確認する。',
            ],
            [
                'title' => '英単語を30個覚える',
                'description' => 'TOEIC対策として英単語帳を使って学習する。',
            ],
            [
                'title' => '30分ランニングする',
                'description' => '体力維持のため近所を30分ほどジョギングする。',
            ],
            [
                'title' => 'ジムで筋トレを行う',
                'description' => '胸と背中を中心にトレーニングメニューを消化する。',
            ],
            [
                'title' => '買い物リストを消化する',
                'description' => 'スーパーで食材と日用品を購入する。',
            ],
            [
                'title' => '部屋を掃除する',
                'description' => '机周りと床を片付けて作業しやすい環境を整える。',
            ],
            [
                'title' => '家計簿を入力する',
                'description' => '今月の支出を確認し、家計簿アプリへ記録する。',
            ],
            [
                'title' => '映画を1本観る',
                'description' => '気になっていた映画を鑑賞して感想をまとめる。',
            ],
            [
                'title' => '旅行計画を立てる',
                'description' => '行き先や宿泊先を調べてスケジュールを作成する。',
            ],
            [
                'title' => '読書を1章進める',
                'description' => '積読になっている本を読み進める。',
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
