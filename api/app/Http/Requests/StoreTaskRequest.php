<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'integer'],
            'priority' => ['required', 'integer'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'ユーザーIDは必須です',
            'user_id.exists' => '存在しないユーザーです',

            'category_id.exists' => '存在しないカテゴリです',

            'title.required' => 'タイトルを入力してください',
            'title.max' => 'タイトルは255文字以内で入力してください',

            'description.string' => '説明は文字列で入力してください',

            'status.required' => 'ステータスを選択してください',

            'priority.required' => '優先度を選択してください',

            'due_date.required' => '期限日を入力してください',
            'due_date.date' => '期限日は正しい日付形式で入力してください',
        ];
    }
}
