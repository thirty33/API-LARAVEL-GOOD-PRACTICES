<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Attributes\ValidationErrorResponseAttribute;
use App\Contracts\API\Auth\AuthServiceInterface;
use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Schema;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {
        parent::__construct();
    }

    #[Post(
        path: '/api/v1/auth/login',
        summary: 'Login an user',
        tags: ['Auth'],
        parameters: [
            new Parameter(
                name: 'email',
                description: 'User email',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
            new Parameter(
                name: 'password',
                description: 'User password',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
            new Parameter(
                name: 'device_name',
                description: 'Device name',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
        ],
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_OK,
        description: 'Successful login',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'User logged in successfully',
                'data' => [
                    'token' => 'string',
                    'token_type' => 'bearer',
                ],
            ]
        )
    )]
    #[ValidationErrorResponseAttribute([
        'email' => 'The email field is required',
        'password' => 'The password field is required',
        'device_name' => 'The device name field is required',
    ])]
    public function __invoke(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request->validated());
    }
}
