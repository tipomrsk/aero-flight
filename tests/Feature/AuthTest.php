<?php

it('should login user', function () {
    $response = $this->post('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token']);
});

it('should not login user with invalid credentials', function () {
    $response = $this->post('/api/login', [
        'email' => 'test2@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Invalid credentials']);
});

it('should logout user', function () use ($token) { // Usa o token armazenado
    $response = $this->withHeader('Authorization', "Bearer $token")
        ->post('/api/logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Logged out']);
})->skip();
