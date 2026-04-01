<?php

namespace Tests7\Feature;

use Final7\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests7\TestCase;

class RegistrationUserTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $userRegData = [
            'email' => 'test@email.ru',
            'password' => '12345677890',
            'name' => 'tester'
        ];
        $response = $this->postJson('/api/user/registration', $userRegData);
        $response->assertStatus(201);
        $responseData = $response->json();
        $this->assertTrue(strlen($responseData['token']) > 0);
    }
}
