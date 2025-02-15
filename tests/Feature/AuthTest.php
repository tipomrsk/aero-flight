<?php

beforeEach(function () {
    $this->token = login();
    $this->withHeader('Accept', 'application/json');
});

it('should logout user', function () {
    $response = $this->withHeader('Authorization', "Bearer {$this->token}")
        ->post('/api/logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'Logged out']);
});

it('should not logout user without token', function () {
    $response = $this->post('/api/logout');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should not logout user with invalid token', function () {
    $response = $this->withHeader('Authorization', 'Bearer invalidToken')
        ->post('/api/logout');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should login user', function () {
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

it('should not login user without email', function () {
    $response = $this->post('/api/login', [
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('should not login user without password', function () {
    $response = $this->post('/api/login', [
        'email' => 'test@exemple.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('should not login user with invalid email', function () {
    $response = $this->post('/api/login', [
        'email' => 'test',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('should not login with valid email and invalid password', function () {
    $response = $this->post('/api/login', [
        'email' => 'pestTest@test.com',
        'password' => 'invalidPassword',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Invalid credentials']);
});

it('should not login user with invalid credentials', function () {
    $response = $this->post('/api/login', [
        'email' => 'test2@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Invalid credentials']);
});
