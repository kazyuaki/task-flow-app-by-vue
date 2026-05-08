<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            Category::create([
                'user_id' => $user->id,
                'name' => '開発',
                'color' => '#1c7ed6',
            ]);

            Category::create([
                'user_id' => $user->id,
                'name' => 'UI/UX',
                'color' => '#d9480f',
            ]);
            
            Category::create([
                'user_id' => $user->id,
                'name' => 'テスト',
                'color' => '#2d6a4f',
            ]);

            Category::create([
                'user_id' => $user->id,
                'name' => 'ドキュメント',
                'color' => '#7c3aed',
            ]);
        });
    }
}
