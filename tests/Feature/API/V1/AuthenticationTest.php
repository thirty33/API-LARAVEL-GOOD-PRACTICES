<?php

namespace Tests\Feature\API\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('api:v1')]
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_unauthenticated_user_cannot_access(): void
    {
        $this
            ->getJson(route('v1.authors.index'))
            ->assertUnauthorized();
    }

    #[Test]
    public function an_user_can_login(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->postJson(route('v1.login'), [
                'email' => $user->email,
                'password' => 'password',
                'device_name' => 'test',
            ])->assertOk();

        $this->assertArrayHasKey('token', $response->json('data'));
        $this->assertArrayHasKey('token_type', $response->json('data'));
    }

    #[Test]
    public function an_user_can_logout(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test')->plainTextToken;

        $this
            ->withToken($token)
            ->postJson(route('v1.logout'))
            ->assertOk();

        $this->assertEmpty($user->tokens);
    }
}
