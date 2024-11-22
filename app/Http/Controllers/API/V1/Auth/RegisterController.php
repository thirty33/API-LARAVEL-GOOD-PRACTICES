<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Attributes\ValidationErrorResponseAttribute;
use App\Contracts\API\Auth\AuthServiceInterface;
use App\Http\Controllers\API\V1\Controller;
use App\Http\Requests\API\V1\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class RegisterController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {
        parent::__construct();
    }

    #[Post(
        path: '/api/v1/auth/register',
        summary: 'Register a new user',
        tags: ['Auth'],
        parameters: [
            new Parameter(
                name: 'name',
                description: 'User name',
                in: 'query',
                required: true,
                schema: new Schema(type: 'string'),
            ),
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
    #[Response(
        response: \Symfony\Component\HttpFoundation\Response::HTTP_CREATED,
        description: 'Successful registration',
        content: new JsonContent(
            schema: 'json',
            example: [
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => [
                    'token' => 'string',
                    'token_type' => 'bearer',
                ],
            ]
        )
    )]
    #[ValidationErrorResponseAttribute([
        'name' => 'The name field is required',
        'email' => 'The email field is required',
        'password' => 'The password field is required',
        'device_name' => 'The device name field is required',
    ])]
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        return $this->authService->register($request->validated());
    }
}
