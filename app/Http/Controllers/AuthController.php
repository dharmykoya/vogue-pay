<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\services\AuthService;
use App\services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        $response = new UserResource($user);
        return $this->successResponse(
            [
                'user' =>$response,
                'token' => $user->createToken('AppName')->accessToken
            ],
            'User created successfully',
            Response::HTTP_CREATED
        );
    }

    public function login(LoginRequest $request)
    {
        $email = $request["email"];
        $password = $request["password"];
        $user = User::whereEmail($email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return $this->errorResponse(
                null,
                'Invalid login credentials',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $token = $user->createToken('AppName')->accessToken;
        $response = [
            'token' => $token,
            'user' => new UserResource($user),
        ];
        return $this->successResponse(
            $response,
            'User logged in successfully',
            Response::HTTP_OK
        );
    }
}
