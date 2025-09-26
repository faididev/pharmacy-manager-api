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
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/auth/login",
 *     operationId="login",
 *     tags={"Authentication"},
 *     summary="User login",
 *     description="Authenticate user and receive access token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="token", type="string", example="1|abc123...")
 *             ),
 *             @OA\Property(property="message", type="string", example="Authenticated"),
 *             @OA\Property(property="status", type="integer", example=200)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid credentials"),
 *             @OA\Property(property="status", type="integer", example=401)
 *         )
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/api/auth/register",
 *     operationId="register",
 *     tags={"Authentication"},
 *     summary="User registration",
 *     description="Register a new user account",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","password_confirmation"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Registration successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="user", type="object",
 *                     @OA\Property(property="id", type="string", example="uuid-123"),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="john.doe@example.com")
 *                 ),
 *                 @OA\Property(property="token", type="string", example="1|abc123...")
 *             ),
 *             @OA\Property(property="message", type="string", example="User registered successfully"),
 *             @OA\Property(property="status", type="integer", example=201)
 *         )
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="User already exists",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User already exists with this email"),
 *             @OA\Property(property="status", type="integer", example=409)
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/auth/user",
 *     operationId="getUser",
 *     tags={"Authentication"},
 *     summary="Get current user",
 *     description="Get current authenticated user information",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User information retrieved",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="string", example="uuid-123"),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="john.doe@example.com")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/api/auth/logout",
 *     operationId="logout",
 *     tags={"Authentication"},
 *     summary="User logout",
 *     description="Logout and invalidate current token",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object"),
 *             @OA\Property(property="message", type="string", example=""),
 *             @OA\Property(property="status", type="integer", example=200)
 *         )
 *     )
 * )
 */
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
