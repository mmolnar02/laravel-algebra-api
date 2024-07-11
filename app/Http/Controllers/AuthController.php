<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token);
    }

    // public function register(RegisterRequest $request)
    // {
    //     $data = array_merge(
    //         $request->validated(),
    //         ['email_verified_at' => now()]
    //     );

    //     $user = User::create($data);

    //     auth()->login($user);

    //     return redirect()->intended('dashboard');
    // }

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
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'refresh_token' => null
        ]);
    }
}
