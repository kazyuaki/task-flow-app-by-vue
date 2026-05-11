<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'category_id', 'title', 'description', 'status', 'priority', 'due_date'])]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    use SoftDeletes;

    // ステータス定数
    public const STATUS_PENDING = 0; // 0: 未着手
    public const STATUS_IN_PROGRESS = 1; // 1: 進行中
    public const STATUS_COMPLETED = 2; // 2: 完了

    // 優先度定数
    public const PRIORITY_LOW = 0; // 0: 低
    public const PRIORITY_MEDIUM = 1; // 1: 中
    public const PRIORITY_HIGH = 2; // 2: 高


    // ステータス表示用
    public const STATUS_LABELS = [
        self::STATUS_PENDING => '未着手',
        self::STATUS_IN_PROGRESS => '進行中',
        self::STATUS_COMPLETED => '完了',
    ];

    // 優先度表示用
    public const PRIORITY_LABELS = [
        self::PRIORITY_LOW => '低',
        self::PRIORITY_MEDIUM => '中',
        self::PRIORITY_HIGH => '高',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
        ];
    }

    // ユーザーとのリレーション
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリーとのリレーション
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // チェックリストとのリレーション
    public function checklists(): HasMany
    {
        return $this->hasMany(TaskChecklist::class)->orderBy('sort_order');
    }
}
