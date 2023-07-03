<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected $endpoint = '/categories';

    /**
     * Get all categories.
     */
    public function test_get_all_categories(): void
    {
        $catergoryCount = 6;
        Category::factory()->count($catergoryCount)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertJsonCount($catergoryCount, 'data');
        $response->assertStatus(200);
    }

    /**
     * Get error a single category.
     */
    public function test_error_get_single_category(): void
    {
        $category = 'fake_url';

        $response = $this->getJson("{$this->endpoint}/{$category}");

        $response->assertStatus(404);
    }

    /**
     * Get a single category.
     */
    public function test_get_single_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$category->url}");

        $response->assertStatus(200);
    }

    /**
     * Validation store category.
     */
    public function test_validation_store_category(): void
    {
        $response = $this->postJson("{$this->endpoint}", [
            'title'         => '',
            'description'   => '',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Validation store category.
     */
    public function test_store_category(): void
    {
        $response = $this->postJson("{$this->endpoint}", [
            'title'         => 'Category 01',
            'description'   => 'Description of category',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Update category.
     */
    public function test_update_category(): void
    {
        $data = [
            'title'         => 'Title Updated',
            'description'   => 'Description Updated',
        ];

        $category = Category::factory()->create();

        $response = $this->putJson("{$this->endpoint}/fake_category", $data);
        $response->assertStatus(404);

        $response = $this->putJson("{$this->endpoint}/{$category->url}", []);
        $response->assertStatus(422);

        $response = $this->putJson("{$this->endpoint}/{$category->url}", $data);
        $response->assertStatus(200);
    }

    /**
     * Delete category.
     */
    public function test_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/fake_category");
        $response->assertStatus(404);

        $response = $this->deleteJson("{$this->endpoint}/{$category->url}");
        $response->assertStatus(204);
    }
}
