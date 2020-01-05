<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const BASE_URI = '/api/categories';

    /**
     * @test
     */
    public function categoriesListIsPaginated()
    {
        factory(Category::class, 30)->create();

        $this->assertCount(30, Category::all());

        $response = $this->json('GET', self::BASE_URI);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'title',
                    'description',
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
    }

    /**
     * @test
     */
    public function categoryCanBeCreated()
    {
        $categoryFake = factory(Category::class)->make();

        $response = $this->json('POST', self::BASE_URI, $categoryFake->toArray());

        $response->assertStatus(201);

        $this->assertCount(1, Category::all());

        $this->assertDatabaseHas('categories', $categoryFake->getAttributes());
    }

    /**
     * @test
     */
    public function categoryCanBeDisplayed()
    {
        $categoryFake = factory(Category::class)->make();
        $this->json('POST', self::BASE_URI, $categoryFake->toArray());

        $category  = Category::first();

        $response = $this->json('GET', self::BASE_URI . '/' . $category->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function categoryCanBeUpdated()
    {
        $categoryFakes = factory(Category::class, 2)->make();
        $this->json('POST', self::BASE_URI, $categoryFakes->first()->toArray());

        $category  = Category::first();

        $response = $this->json('PUT', self::BASE_URI . '/' . $category->id, $categoryFakes->last()->toArray());

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', $categoryFakes->last()->getAttributes());
    }

    /**
     * @test
     */
    public function categoryCanBeDeleted()
    {
        $categoryFake = factory(Category::class)->make();
        $this->json('POST', self::BASE_URI, $categoryFake->toArray());

        $this->assertCount(1, Category::all());

        $category  = Category::first();

        $response = $this->json('DELETE', self::BASE_URI . '/' . $category->id);

        $response->assertStatus(204);
        $this->assertCount(0, Category::all());

        $this->assertDatabaseMissing('categories', $categoryFake->getAttributes());
    }
}
