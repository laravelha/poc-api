<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Post;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const BASE_URI = '/api/posts';

    /**
     * @test
     */
    public function postsListIsPaginated()
    {
        factory(Post::class, 30)->create();

        $this->assertCount(30, Post::all());

        $response = $this->json('GET', self::BASE_URI);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'title',
                    'content',
                    'published_at',
                    'category_id',
                ]
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => [
                'current_page', 'last_page', 'from', 'to',
                'path', 'per_page', 'total'
            ]
        ]);
    }

    /**
     * @test
     */
    public function checkRequiredFields()
    {
        $response = $this->json('POST', self::BASE_URI, []);

        $response->assertJsonValidationErrors('title');
        $response->assertJsonValidationErrors('content');
        $response->assertJsonValidationErrors('category_id');
    }

    /**
     * @test
     */
    public function postCanBeCreated()
    {
        $postFake = factory(Post::class)->make();

        $response = $this->json('POST', self::BASE_URI, $postFake->toArray());

        $response->assertStatus(201);

        $this->assertCount(1, Post::all());

        $this->assertDatabaseHas('posts', $postFake->getAttributes());
    }

    /**
     * @test
     */
    public function postCanBeDisplayed()
    {
        $postFake = factory(Post::class)->make();
        $this->json('POST', self::BASE_URI, $postFake->toArray());

        $post  = Post::first();

        $response = $this->json('GET', self::BASE_URI . '/' . $post->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function postCanBeUpdated()
    {
        $postFakes = factory(Post::class, 2)->make();
        $this->json('POST', self::BASE_URI, $postFakes->first()->toArray());

        $post  = Post::first();

        $response = $this->json('PUT', self::BASE_URI . '/' . $post->id, $postFakes->last()->toArray());

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', $postFakes->last()->getAttributes());
    }

    /**
     * @test
     */
    public function postCanBeDeleted()
    {
        $postFake = factory(Post::class)->make();
        $this->json('POST', self::BASE_URI, $postFake->toArray());

        $this->assertCount(1, Post::all());

        $post  = Post::first();

        $response = $this->json('DELETE', self::BASE_URI . '/' . $post->id);

        $response->assertStatus(204);
        $this->assertCount(0, Post::all());

        $this->assertDatabaseMissing('posts', $postFake->getAttributes());
    }
}
