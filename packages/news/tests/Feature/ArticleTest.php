<?php

namespace Laravelha\News\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravelha\News\Tests\TestCase;
use Laravelha\News\Models\Article;

class ArticleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const BASE_URI = '/news/api/articles';

    /**
     * @test
     */
    public function articlesListIsPaginated()
    {
        factory(Article::class, 30)->create();

        $this->assertCount(30, Article::all());

        $response = $this->json('GET', self::BASE_URI);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'title',
                    'content',
                    'published_at',
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
    }

    /**
     * @test
     */
    public function articleCanBeCreated()
    {
        $articleFake = factory(Article::class)->make();

        $response = $this->json('POST', self::BASE_URI, $articleFake->toArray());

        $response->assertStatus(201);

        $this->assertCount(1, Article::all());

        $this->assertDatabaseHas('articles', $articleFake->getAttributes());
    }

    /**
     * @test
     */
    public function articleCanBeDisplayed()
    {
        $articleFake = factory(Article::class)->make();
        $this->json('POST', self::BASE_URI, $articleFake->toArray());

        $article  = Article::first();

        $response = $this->json('GET', self::BASE_URI . '/' . $article->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function articleCanBeUpdated()
    {
        $articleFakes = factory(Article::class, 2)->make();
        $this->json('POST', self::BASE_URI, $articleFakes->first()->toArray());

        $article  = Article::first();

        $response = $this->json('PUT', self::BASE_URI . '/' . $article->id, $articleFakes->last()->toArray());

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', $articleFakes->last()->getAttributes());
    }

    /**
     * @test
     */
    public function articleCanBeDeleted()
    {
        $articleFake = factory(Article::class)->make();
        $this->json('POST', self::BASE_URI, $articleFake->toArray());

        $this->assertCount(1, Article::all());

        $article  = Article::first();

        $response = $this->json('DELETE', self::BASE_URI . '/' . $article->id);

        $response->assertStatus(204);
        $this->assertCount(0, Article::all());

        $this->assertDatabaseMissing('articles', $articleFake->getAttributes());
    }
}
