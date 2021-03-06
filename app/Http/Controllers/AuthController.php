<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\services\AuthService;
use Illuminate\Http\Request;
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
}
