<?php

namespace Tests\Feature\API\V1;

use App\Models\Genre;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;


#[Group('api:v1')]
#[Group('api:v1:genres')]
class GenreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_unauthenticated_user_cannot_access(): void
    {
        $this
            ->getJson(route('v1.genres.index'))
            ->assertUnauthorized();
    }

    #[Test]
    public function genres_can_be_listed(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        Genre::factory(10)->create();

        $response = $this
            ->withToken($token)
            ->getJson(route('v1.genres.index'))
            ->assertOk();

        $this->assertCount(10, $response->json('data'));

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                ]
            ],
        ]);
    }

    #[Test]
    public function a_genre_can_be_retrieved(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        $genre = Genre::factory()->create();

        $response = $this
            ->withToken($token)
            ->getJson(route('v1.genres.show', $genre))
            ->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $genre->id,
                'name' => $genre->name,
                'created_at' => $genre->created_at->format(format: 'Y-m-d H:i:s'),
            ]
        ]);
    }

    #[Test]
    public function a_genre_can_be_created(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson(route('v1.genres.store'), [
                'name' => 'Fantasy',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('genres', [
            'name' => 'Fantasy',
        ]);

        $response->assertJson([
            'data' => [
                'name' => 'Fantasy',
            ]
        ]);
    }

    #[Test]
    public function a_genre_can_be_updated(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        $genre = Genre::factory()->create();

        $response = $this
            ->withToken($token)
            ->putJson(route('v1.genres.update', $genre), [
                'name' => 'Fantasy',
            ])
            ->assertOk();

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => 'Fantasy',
        ]);

        $response->assertJson([
            'data' => [
                'id' => $genre->id,
                'name' => 'Fantasy',
            ]
        ]);
    }

    #[Test]
    public function a_genre_can_be_deleted(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        $genre = Genre::factory()->create();

        $this
            ->withToken($token)
            ->deleteJson(route('v1.genres.destroy', $genre))
            ->assertOk();
    }
}
