<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->create($request->validated());

        return $this->successResponse(
            new UserResource($user),
            'User registered successfully',
            201
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return $this->unauthorizedResponse('Invalid credentials');
        }

        // Regenerate session for security (if session exists)
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return $this->successResponse(
            new UserResource(Auth::user()),
            'User logged in successfully'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        // For SPA authentication, we revoke the current access token if it exists
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        // Also invalidate session if it exists
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return $this->successResponse(
            null,
            'User logged out successfully'
        );
    }
}
