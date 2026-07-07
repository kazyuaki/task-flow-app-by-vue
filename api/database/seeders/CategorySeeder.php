<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\DefaultCategoryService;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(DefaultCategoryService $defaultCategoryService): void
    {
        User::all()->each(function (User $user) use ($defaultCategoryService) {
            $defaultCategoryService->ensureFor($user);
        });
    }
}
