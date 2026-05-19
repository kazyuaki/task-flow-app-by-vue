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
                'name' => '仕事',
                'color' => '#1c7ed6',
            ]);
    
            Category::create([
                'user_id' => $user->id,
                'name' => '学習',
                'color' => '#7c3aed',
            ]);
    
            Category::create([
                'user_id' => $user->id,
                'name' => '運動',
                'color' => '#2d6a4f',
            ]);
    
            Category::create([
                'user_id' => $user->id,
                'name' => '生活',
                'color' => '#d9480f',
            ]);
    
            Category::create([
                'user_id' => $user->id,
                'name' => '趣味',
                'color' => '#f59f00',
            ]);
    
            Category::create([
                'user_id' => $user->id,
                'name' => 'その他',
                'color' => '#868e96',
            ]);
        });
    }
}
