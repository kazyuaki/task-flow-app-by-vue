<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;

class UpdateProfileController extends Controller
{
    /**
     * プロフィールを更新する
     */
    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $request->user();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return response()->json([
            'message' => 'プロフィールを更新しました。',
            'user' => $user->fresh(),
        ]);
    }
}