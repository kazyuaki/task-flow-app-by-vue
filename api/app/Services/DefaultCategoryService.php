<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;

class DefaultCategoryService
{
    private const CATEGORIES = [
        ['name' => '仕事', 'color' => '#1c7ed6'],
        ['name' => '学習', 'color' => '#7c3aed'],
        ['name' => '運動', 'color' => '#2d6a4f'],
        ['name' => '生活', 'color' => '#d9480f'],
        ['name' => '趣味', 'color' => '#f59f00'],
        ['name' => 'その他', 'color' => '#868e96'],
    ];

    public function ensureFor(User $user): void
    {
        foreach (self::CATEGORIES as $category) {
            Category::firstOrCreate(
                ['user_id' => $user->id, 'name' => $category['name']],
                ['color' => $category['color']],
            );
        }
    }
}
