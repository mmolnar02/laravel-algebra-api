<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token, auth()->user());
    }

    public function register(RegisterRequest $request)
    {
        $data = array_merge(
            $request->validated(),
            ['email_verified_at' => now()]
        );

        $user = User::create($data);

        $token = auth()->login($user);

        return $this->respondWithToken($token, $user);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        //JWTAuth::invalidate(auth()->token());
        //$user = JWTAuth::authenticate($refreshToken);
        $newToken = JWTAuth::setToken($refreshToken)->refresh();


        return $this->respondWithToken($newToken, null);
    }

    public function logout()
    {
        return auth()->logout(true);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token, ?User $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'refresh_token' => $user ? JWTAuth::fromUser($user): null
        ]);
    }
}
