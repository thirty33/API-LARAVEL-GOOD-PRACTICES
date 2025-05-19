<?php

namespace Tests\Feature\API\V1;

use App\Models\Author;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;

#[Group('api:v1')]
#[Group('api:v1:authors')]
class AuthorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authors_can_be_listed(): void
    {

        $token = User::factory()->create()->createToken('test')->plainTextToken;

        Author::factory(10)->create();

        $response = $this
            ->withToken($token)
            ->getJson(route('v1.authors.index'))
            ->assertOk();

        $this->assertCount(10, $response->json('data.data'));

        $response
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'created_at',
                        ]
                    ],
                    'current_page',
                    'first_page_url',
                ]
            ]);
    }

    #[Test]
    public function an_author_can_be_retrieved(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        $author = Author::factory()->create();

        $response = $this
            ->withToken($token)
            ->getJson(route('v1.authors.show', $author))
            ->assertOk();

        $response
            ->assertJson([
                'data' => [
                    'id' => $author->id,
                    'name' => $author->name,
                    'created_at' => $author->created_at->format(format: 'Y-m-d H:i:s'),
                ]
            ]);
    }

    #[Test]
    public function an_author_can_be_created(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson(route(name: 'v1.authors.store'), [
                'name' => 'John Doe',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('authors', [
            'name' => 'John Doe',
        ]);

        $response->assertJson([
            'data' => [
                'name' => 'John Doe',
            ]
        ]);
    }

    #[Test]
    public function an_author_can_be_updated(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        $author = Author::factory()->create();

        $response = $this
            ->withToken($token)
            ->putJson(route('v1.authors.update', $author), [
                'name' => 'Jane Doe',
            ])
            ->assertOk();

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'Jane Doe',
        ]);

        $response->assertJson([
            'data' => [
                'id' => $author->id,
                'name' => 'Jane Doe',
            ]
        ]);
    }

    #[Test]
    public function an_author_can_be_deleted(): void
    {
        $token = User::factory()->create()->createToken(name: 'test')->plainTextToken;

        $author = Author::factory()->create();

        $this
            ->withToken($token)
            ->deleteJson(route('v1.authors.destroy', $author))
            ->assertOk();

        $this->assertDatabaseMissing('authors', [
            'id' => $author->id,
        ]);

        $this->assertDatabaseCount('authors', count: 0);
    }
}
