<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
        'current_password' => [
            'required',
            'current_password',
        ],
        'password' => [
            'required',
            'confirmed',
            'min:8',
        ],
    ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => '現在のパスワードを入力してください。',
            'current_password.current_password' => '現在のパスワードが正しくありません。',
    
            'password.required' => '新しいパスワードを入力してください。',
            'password.confirmed' => '確認用パスワードが一致しません。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
        ];
    }
}
