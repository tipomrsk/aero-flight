<?php

beforeEach(function () {
    $this->token = login();
    $this->withHeader('Authorization', "Bearer {$this->token}");
});

it('should create a new order travel', function () {
    $response = $this->post('/api/order-travel', [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'uuid',
            'origin',
            'destination',
            'start_date',
            'end_date',
        ]);
});

it('should not create a new order travel without origin', function () {
    $response = $this->post('/api/order-travel', [
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['origin']);
});

it('should not create a new order travel without destination', function () {
    $response = $this->post('/api/order-travel', [
        'origin' => 'Porto Alegre',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['destination']);
});

it('should not create a new order travel without start date', function () {
    $response = $this->post('/api/order-travel', [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_date']);
});

it('should not create a new order travel without end date', function () {
    $response = $this->post('/api/order-travel', [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});

it('should not create a new order travel with invalid start date', function () {
    $response = $this->post('/api/order-travel', [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => 'invalid',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_date']);
});

it('should not create a new order travel with invalid end date', function () {
    $response = $this->post('/api/order-travel', [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => 'invalid',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});

it('should not create a new order travel with end date before start date', function () {
    $response = $this->post('/api/order-travel', [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-10',
        'end_date' => '2025-04-01',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});
