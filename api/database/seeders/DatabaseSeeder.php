<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(3)->create()->each(function ($user) {
            $categories = Category::factory(3)->create([
                'user_id' => $user->id,
            ]);
            Task::factory(10)->create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
            ]);
        });

        $this->call([
            UserSeeder::class,
        ]);

    }
}
