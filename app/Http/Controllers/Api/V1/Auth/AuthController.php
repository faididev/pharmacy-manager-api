<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\LoginUserDto;
use App\DTOs\Auth\RegisterUserDto;
use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\V1\RegisterUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{

    public function user()
    {
        return new UserResource(auth()->user());
    }

    public function login(LoginRequest $loginRequest)
    {
        $loginRequest->validated();

        $authUserDto = new LoginUserDto(...$loginRequest->validated());

        if (!Auth::attempt($authUserDto->toArray())) {
            return $this->error('Invalid credentials', 401);
        }

        $user = User::firstWhere('email', $authUserDto->email);

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API token for ' . $user->email,
                    ['*'],
                    now()->addMonth())->plainTextToken
            ]
        );
    }

    public function register(RegisterUserRequest $registerRequest)
    {

        $registerUserDto = RegisterUserDto::fromRequest($registerRequest->validated());

        // Check if user already exists
        if (User::where('email', $registerUserDto->email)->exists()) {
            return $this->error('User already exists with this email', 409);
        }

        $user = User::create([
            'name' => $registerUserDto->name,
            'email' => $registerUserDto->email,
            'password' => Hash::make($registerUserDto->password),
        ]);

        // Create token for the new user
        $token = $user->createToken(
            'API token for ' . $user->email,
            ['*'],
            now()->addMonth()
        )->plainTextToken;

        return $this->ok(
            'User registered successfully',
            [
                'user' => [
                    'id' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                ],
                'token' => $token
            ],
            201
        );
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('');
    }
}
