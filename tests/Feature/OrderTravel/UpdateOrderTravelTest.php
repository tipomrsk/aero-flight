<?php

beforeEach(function (): void {
    $this->token = login();
    $this->withHeader('Authorization', "Bearer {$this->token}");
    $this->withHeader('Accept', "application/json");

    $this->orderTravel = createOrderTravel();
});

it('should update order travel', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => 'Porto Alegre',
            'destination' => 'São Paulo',
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-10',
            'status' => $this->orderTravel->status,
        ]);
});

it('should not update order travel with invalid id', function (): void {
    $response = $this->put('/api/order-travel/invalidId', [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should not update order travel without token', function (): void {
    $response = $this->withHeader('Authorization', "Bearer invalidToken")
        ->put("/api/order-travel/{$this->orderTravel->uuid}", [
            'origin' => 'Porto Alegre',
            'destination' => 'São Paulo',
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-10',
        ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should not update order travel of another user', function (): void {
    login();

    $orderTravel = createOrderTravel([
        'user_id' => 2,
    ]);

    $response = $this->put("/api/order-travel/{$orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should update order travel without origin', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => $this->orderTravel->origin,
            'destination' => 'São Paulo',
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-10',
            'status' => $this->orderTravel->status,
        ]);
});

it('should update order travel without destination', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => 'Porto Alegre',
            'destination' => $this->orderTravel->destination,
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-10',
            'status' => $this->orderTravel->status,
        ]);
});

it('should update order travel without start date', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => 'Porto Alegre',
            'destination' => 'São Paulo',
            'start_date' => $this->orderTravel->start_date,
            'end_date' => '2025-04-10',
            'status' => $this->orderTravel->status,
        ]);
});

it('should update order travel without end date', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => 'Porto Alegre',
            'destination' => 'São Paulo',
            'start_date' => '2025-04-01',
            'end_date' => $this->orderTravel->end_date,
            'status' => $this->orderTravel->status,
        ]);
});

it('should not update order travel with invalid start date', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => 'invalidDate',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_date']);
});

it('should not update order travel with invalid end date', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => 'invalidDate',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});

it('should not update order travel with invalid end date before start date', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-10',
        'end_date' => '2025-04-01',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});

it('should not update order travel with status', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
        'status' => 'approved',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Status cannot be updated']);
});

it('should not update order travel with invalid field', function (): void {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'invalidField' => 'value',
    ]);

    $response->assertStatus(500)
        ->assertJson(["message" => "Incorret payload"]);
});
