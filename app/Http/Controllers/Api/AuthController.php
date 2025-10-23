<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->create($data);
        $token = JWTAuth::fromUser($user);
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!($token = JWTAuth::attempt($credentials))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        return response()->json(['token' => $token, 'user' => auth()->user()]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logged out']);
    }
}
