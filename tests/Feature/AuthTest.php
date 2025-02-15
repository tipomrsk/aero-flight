<?php

beforeEach(function (): void {
    $this->token = login();
    $this->withHeader('Accept', 'application/json');
});

it('should logout user', function (): void {
    $response = $this->withHeader('Authorization', "Bearer {$this->token}")
        ->post('/api/logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Logged out']);
});

it('should not logout user without token', function (): void {
    $response = $this->post('/api/logout');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should not logout user with invalid token', function (): void {
    $response = $this->withHeader('Authorization', 'Bearer invalidToken')
        ->post('/api/logout');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should login user', function (): void {
    login([
        'email' => 'pestTest@test.com',
        'password' => 'password',
    ]);

    $response = $this->post('/api/login', [
        'email' => 'pestTest@test.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token']);
});

it('should not login user without email', function (): void {
    $response = $this->post('/api/login', [
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('should not login user without password', function (): void {
    $response = $this->post('/api/login', [
        'email' => 'test@exemple.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('should not login user with invalid email', function (): void {
    $response = $this->post('/api/login', [
        'email' => 'test',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('should not login with valid email and invalid password', function (): void {
    $response = $this->post('/api/login', [
        'email' => 'pestTest@test.com',
        'password' => 'invalidPassword',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'User not found']);
});

it('should not login user with invalid credentials', function (): void {
    $response = $this->post('/api/login', [
        'email' => 'test2@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'User not found']);
});
