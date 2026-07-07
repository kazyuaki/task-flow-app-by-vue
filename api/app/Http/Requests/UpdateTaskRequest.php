<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('user_id', $this->user()->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'integer'],
            'priority' => ['required', 'integer'],
            'due_date' => ['nullable', 'date'],
            'checklist' => ['nullable', 'array'],
            'checklist.*.id' => [
                'nullable',
                'integer',
                Rule::exists('task_checklists', 'id')
                    ->where('task_id', $this->route('task')->id),
            ],
            'checklist.*.label' => ['required', 'string', 'max:255'],
            'checklist.*.done' => ['boolean'],
            'checklist.*.sort_order' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => '存在しないカテゴリです',

            'title.required' => 'タイトルを入力してください',
            'title.max' => 'タイトルは255文字以内で入力してください',

            'description.string' => '説明は文字列で入力してください',

            'status.required' => 'ステータスを選択してください',

            'priority.required' => '優先度を選択してください',

            'due_date.date' => '期限日は正しい日付形式で入力してください',

            'checklist.*.label.required' => 'チェックリストの項目名を入力してください',
            'checklist.*.label.max' => 'チェックリストの項目名は255文字以内で入力してください',
        ];
    }
}
