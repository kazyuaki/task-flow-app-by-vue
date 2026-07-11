<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    /**
     * パスワードを変更する
     */
    public function __invoke(UpdatePasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'パスワードを更新しました。',
        ]);
    }
}