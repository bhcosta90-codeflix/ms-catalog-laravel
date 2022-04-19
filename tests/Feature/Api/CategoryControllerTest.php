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

        $response = $this->postJson($this->endpoint, [
            'name' => 'teste de categoria',
            'description' => 'teste de descricao',
            'is_active' => false,
        ]);

        $this->assertEquals('teste de descricao', $response->json('data.description'));
        $this->assertFalse($response->json('data.is_active'));

        $this->assertDatabaseHas('categories', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'description' => $response->json('data.description'),
            'is_active' => $response->json('data.is_active'),
        ]);
    }

    public function test_not_found_update()
    {
        $response = $this->putJson($this->endpoint . '/fake-id', [
            'name' => 'new name'
        ]);
        $response->assertStatus(404);
    }

    public function test_validation_update()
    {
        $category = Category::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $category->id, []);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function test_update()
    {
        $category = Category::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $category->id, [
            'name' => 'new name',
        ]);

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

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'new name',
            'description' => $category->description,
            'is_active' => true,
        ]);

        $response = $this->putJson($this->endpoint . '/' . $category->id, [
            'name' => 'new name 2',
            'is_active' => false,
            'description' => 'new description',
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'new name 2',
            'description' => 'new description',
            'is_active' => false,
        ]);

        $response = $this->putJson($this->endpoint . '/' . $category->id, [
            'name' => 'new name 2',
            'description' => 'new description',
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'new name 2',
            'description' => 'new description',
            'is_active' => false,
        ]);
    }

    public function test_not_found_destroy()
    {
        $response = $this->deleteJson($this->endpoint . '/fake_value');
        $response->assertStatus(404);
    }

    public function test_destroy()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson($this->endpoint . '/' . $category->id);
        $response->assertNoContent();
    }
}
