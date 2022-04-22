<?php

namespace Tests\Feature\Api;

use App\Models\CastMember as Model;
use Costa\Core\Modules\CastMember\Enums\CastMemberType;
use Tests\TestCase;

class CastMemberControllerTest extends TestCase
{
    protected string $endpoint = '/api/cast_members';

    public function testListEmpty()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function testList()
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
                'type',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function testStoreValidation()
    {
        $response = $this->postJson($this->endpoint, []);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'type',
            ]
        ]);

        $response = $this->postJson($this->endpoint, [
            'name' => 'teste',
            'type' => '0',
        ]);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'type',
            ]
        ]);
    }

    public function testStore()
    {
        $response = $this->postJson($this->endpoint, [
            'name' => 'teste de membro',
            'type' => CastMemberType::ACTOR
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function testUpdateEmpty()
    {
        $response = $this->putJson($this->endpoint . '/fake-id', [
            'name' => 'new name',
            'type' => CastMemberType::ACTOR
        ]);
        $response->assertStatus(404);
    }

    public function testUpdateValidation()
    {
        $model = Model::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $model->id, []);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'type'
            ]
        ]);

        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'teste'
        ]);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'type'
            ]
        ]);
    }

    public function testUpdate()
    {
        $model = Model::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'new name',
            'type' => CastMemberType::ACTOR
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'created_at',
                'updated_at',
            ]
        ]);

        $this->assertDatabaseHas('cast_members', [
            'id' => $model->id,
            'name' => 'new name',
            'type' => 2,
        ]);

        $response = $this->putJson($this->endpoint . '/' . $model->id, [
            'name' => 'new name',
            'type' => CastMemberType::DIRECTOR
        ]);

        $this->assertDatabaseHas('cast_members', [
            'id' => $model->id,
            'name' => 'new name',
            'type' => 1,
        ]);
    }

    public function testDestroyEmtpy()
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
