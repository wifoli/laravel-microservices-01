<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    protected $endpoint = '/companies';

    /**
     * Get all companies.
     */
    public function test_get_all_companies(): void
    {
        $companyCount = 1;
        Company::factory()->count($companyCount)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertJsonCount($companyCount, 'data');
        $response->assertStatus(200);
    }

    /**
     * Get error a single company.
     */
    public function test_error_get_single_company(): void
    {
        $company = 'fake_uuid';

        $response = $this->getJson("{$this->endpoint}/{$company}");

        $response->assertStatus(404);
    }

    /**
     * Get a single company.
     */
    public function test_get_single_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$company->uuid}");

        $response->assertStatus(200);
    }

    /**
     * Validation store company.
     */
    public function test_validation_store_company(): void
    {
        $response = $this->postJson("{$this->endpoint}", [
            'title'         => '',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Store company.
     */
    public function test_store_company(): void
    {
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('image-fake.png');

        $response = $this->call(
            'POST',
            $this->endpoint,
            [
                'category_id'   => $category->id,
                'name'          => 'MyCompany',
                'email'         => 'email@email.com',
                'whatsapp'      => '123456789',
            ],
            [],
            [
                'image' => $image
            ],
        );

        $response->assertStatus(201);
    }

    /**
     * Update company.
     */
    public function test_update_company(): void
    {
        $category = Category::factory()->create();
        $company = Company::factory()->create();

        $data = [
            'category_id'   => $category->id,
            'name'          => 'MyCompany',
            'email'         => 'email@email.com',
            'whatsapp'      => '123456789',
        ];

        $response = $this->putJson("{$this->endpoint}/fake_company", $data);
        $response->assertStatus(404);

        $response = $this->putJson("{$this->endpoint}/{$company->uuid}", []);
        $response->assertStatus(422);

        $response = $this->putJson("{$this->endpoint}/{$company->uuid}", $data);
        $response->assertStatus(200);
    }

    /**
     * Delete company.
     */
    public function test_delete_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("{$this->endpoint}/fake_company");
        $response->assertStatus(404);

        $response = $this->deleteJson("{$this->endpoint}/{$company->uuid}");
        $response->assertStatus(204);
    }
}
