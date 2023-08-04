<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\LoginUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credentials don\'t match'], 401);
        }
        $user = User::where('email', $request->email)->first();
        if ($user->tokens()->where('tokenable_id', $user->id)->exists()) {
            $user->tokens()->delete();
        }
        $expiresAt = now()->addMinutes(1440); // Set the expiration time to 24 hours from now

        $token = $user->createToken('Api token of ' . $user->name, ['expires_at' => $expiresAt])->plainTextToken;
        $url = null;
        if ($user->profile_picture) {
            $url = asset("storage/profile_pictures/"  . $user->profile_picture);
        }
        return $this->success([
            'user' => $user,
            'token' => $token,
            'profile' => $url,
        ]);
    }
    public function Verifytoken(Request  $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || Carbon::parse($accessToken->expires_at)->isPast()) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        $user = $accessToken->tokenable; /* gives the user by its token */

        return response()->json(['success' => 'valid token'], 200);
    }
}
