<?php

namespace Tests7\Feature;

use Final7\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests7\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $userRegData = [
            'email' => 'test@email.ru',
            'password' => '12345677890',
            'name' => 'tester'
        ];
        $userLoginData = [
            'email' => $userRegData['email'],
            'password' => $userRegData['password']
        ];

        $response = $this->postJson('/api/user/registration', $userRegData);
        $response->assertStatus(201);
        $responseData = $response->json();
        $this->assertTrue(strlen($responseData['token']) > 0);

        $response = $this->postJson('/api/user/login', $userLoginData);
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue(strlen($responseData['token']) > 0);


        $userLoginData['email'] = 'wrong@mail.ru';

        $response = $this->postJson('/api/user/login', $userLoginData);
        $response->assertStatus(422);
    }
}
