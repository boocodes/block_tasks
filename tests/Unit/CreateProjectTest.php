<?php

namespace Tests7\Feature;

use Final7\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests7\TestCase;

class CreateProjectTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $projectData = [
            'name' => 'hello test',
        ];

        $response = $this->postJson('/api/projects', $projectData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', [
            'name' => $projectData['name'],

        ]);
    }
}
