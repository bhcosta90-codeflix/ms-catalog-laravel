<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Genre as Model;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    protected string $endpoint = '/api/genres';

    public function testListEmptyGenres()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function testListGenres()
    {
        Model::factory(35)->create();

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

        $response->assertJsonCount(15, 'data');
        $this->assertEquals(3, $response->json('meta.last_page'));

        $response = $this->getJson($this->endpoint . '?page=3');
        $this->assertEquals(3, $response->json('meta.current_page'));
        $response->assertJsonCount(5, 'data');
    }

    public function testGetEmpty()
    {
        $response = $this->getJson($this->endpoint . '/fake-value');
        $response->assertStatus(404);
    }

    public function testGet()
    {
        $model = Model::factory()->create();
        $response = $this->getJson($this->endpoint . '/' . $model->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function testStoreValidate()
    {
        $response = $this->postJson($this->endpoint, []);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'categories'
            ]
        ]);

        $response = $this->postJson($this->endpoint, [
            'name' => 'teste',
            'categories' => 'a',
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories'
            ]
        ]);

        $response = $this->postJson($this->endpoint, [
            'name' => 'teste',
            'categories' => ['a'],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories'
            ]
        ]);

        $response = $this->postJson($this->endpoint, [
            'name' => 'teste',
            'categories' => [str()->uuid()],
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories'
            ]
        ]);
    }

    public function testStore()
    {
        $category = Category::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'name' => 'teste de categoria',
            'categories' => [$category->id]
        ]);

        $this->assertDatabaseCount('category_genre', 1);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
            ]
        ]);

        $response = $this->postJson($this->endpoint, [
            'name' => 'teste de categoria',
            'is_active' => false,
            'categories' => [$category->id]
        ]);

        $this->assertFalse($response->json('data.is_active'));
        $this->assertDatabaseCount('category_genre', 2);

        $this->assertDatabaseHas('genres', [
            'id' => $response->json('data.id'),
            'name' => $response->json('data.name'),
            'is_active' => $response->json('data.is_active'),
        ]);
    }

    public function testNotFoundUpdate()
    {
        $category = Category::factory()->create();

        $response = $this->putJson($this->endpoint . '/fake-id', [
            'name' => 'new name',
            'categories' => [$category->id]
        ]);
        $response->assertStatus(404);
    }

    public function testUpdateValidate()
    {
        $model = Model::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $model->id, []);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'categories',
            ]
        ]);

        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'teste',
        ]);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories',
            ]
        ]);

        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'teste',
            'categories' => 'a'
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories',
            ]
        ]);

        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'teste',
            'categories' => ['a']
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories',
            ]
        ]);

        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'teste',
            'categories' => [str()->uuid()]
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'categories',
            ]
        ]);
    }

    public function testUpdate()
    {
        $category = Category::factory()->create();
        $model = Model::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'new name',
            'categories' => [$category->id]
        ]);
        $this->assertDatabaseCount('category_genre', 1);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
            ]
        ]);

        $this->assertDatabaseHas('genres', [
            'id' => $model->id,
            'name' => 'new name',
            'is_active' => true,
        ]);

        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'new name 2',
            'is_active' => false,
            'categories' => [$category->id]
        ]);

        $this->assertDatabaseCount('category_genre', 1);
        $this->assertDatabaseHas('genres', [
            'id' => $model->id,
            'name' => 'new name 2',
            'is_active' => false,
        ]);

        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'new name 2',
            'categories' => [$category->id]
        ]);

        $this->assertDatabaseHas('genres', [
            'id' => $model->id,
            'name' => 'new name 2',
            'is_active' => false,
        ]);
        $this->assertDatabaseCount('category_genre', 1);
    }

    public function testNotFoundDestroy()
    {
        $response = $this->deleteJson($this->endpoint . '/fake_value');
        $response->assertStatus(404);
    }

    public function testDestroy()
    {
        $model = Model::factory()->create();
        $response = $this->deleteJson($this->endpoint . '/' . $model->id);
        $response->assertNoContent();

        $this->assertSoftDeleted($model);
    }
}
