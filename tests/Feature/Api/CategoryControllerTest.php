<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    protected string $endpoint = '/api/categories';

    public function test_list_empty_categories()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
    }

    public function test_list_categories()
    {
        Category::factory(35)->create();

        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ]
        ]);
    }

    public function test_get_empty_category()
    {
        $response = $this->getJson($this->endpoint . '/fake-value');
        $response->assertStatus(404);
    }

    public function test_get_category()
    {
        $category = Category::factory()->create();
        $response = $this->getJson($this->endpoint . '/' . $category->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function test_validation_store()
    {
        $response = $this->postJson($this->endpoint, []);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function test_store()
    {
        $response = $this->postJson($this->endpoint, [
            'name' => 'teste de categoria'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function test_destroy()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson($this->endpoint . '/' . $category->id);
        $response->assertNoContent();
    }
}
