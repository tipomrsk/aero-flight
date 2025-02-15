<?php

beforeEach(function (): void {
    $this->token = login();
    $this->withHeader('Authorization', "Bearer {$this->token}");
    $this->withHeader('Accept', "application/json");

    $this->orderTravel = createOrderTravel();
});

it('should block if not admin', function (): void {
    $response = $this->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
        'status' => 'approved',
    ]);

    $response->assertStatus(403)
        ->assertJson(['message' => 'Unauthorized']);
});

it('should update order travel status to approved', function (): void {
    $adminToken = login([
        'is_admin' => true,
    ]);

    $response = $this->withHeader('Authorization', "Bearer {$adminToken}")
        ->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
            'status' => 'approved',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => $this->orderTravel->origin,
            'destination' => $this->orderTravel->destination,
            'start_date' => $this->orderTravel->start_date,
            'end_date' => $this->orderTravel->end_date,
            'status' => 'approved',
        ]);
});

it('should update order travel status to canceled', function (): void {
    $adminToken = login([
        'is_admin' => true,
    ]);

    $response = $this->withHeader('Authorization', "Bearer {$adminToken}")
        ->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
            'status' => 'canceled',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => $this->orderTravel->origin,
            'destination' => $this->orderTravel->destination,
            'start_date' => $this->orderTravel->start_date,
            'end_date' => $this->orderTravel->end_date,
            'status' => 'canceled',
        ]);
});

it('should not update order travel status with invalid status', function (): void {
    $adminToken = login([
        'is_admin' => true,
    ]);

    $response = $this->withHeader('Authorization', "Bearer {$adminToken}")
        ->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
            'status' => 'invalidStatus',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});

it('should not update order travel status with invalid uuid', function (): void {
    $adminToken = login([
        'is_admin' => true,
    ]);

    $response = $this->withHeader('Authorization', "Bearer {$adminToken}")
        ->put("/api/order-travel/update-status/invalidUuid", [
            'status' => 'approved',
        ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should not cancel order travel already approved', function (): void {
    $adminToken = login([
        'is_admin' => true,
    ]);

    $orderTravel = createOrderTravel([
        'status' => 'approved',
    ]);

    $response = $this->withHeader('Authorization', "Bearer {$adminToken}")
        ->put("/api/order-travel/update-status/{$orderTravel->uuid}", [
            'status' => 'canceled',
        ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should not update order travel without token', function (): void {
    $response = $this->withHeader('Authorization', "Bearer invalidToken")
        ->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
            'status' => 'approved',
        ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should not update to pending an order travel', function (): void {
    $adminToken = login([
        'is_admin' => true,
    ]);

    $response = $this->withHeader('Authorization', "Bearer {$adminToken}")
        ->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
            'status' => 'pending',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});
