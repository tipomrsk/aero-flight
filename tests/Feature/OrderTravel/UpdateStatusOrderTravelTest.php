<?php

beforeEach(function () {
    $this->token = login();
    $this->withHeader('Authorization', "Bearer {$this->token}");
    $this->withHeader('Accept', "application/json");

    $this->orderTravel = createOrderTravel();
});

it('user should not update his order travel', function () {
    $response = $this->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
        'status' => 'completed',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should not update order travel with invalid id', function () {
    $response = $this->put('/api/order-travel/update-status/invalidId', [
        'status' => 'completed',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should not update order travel without token', function () {
    $response = $this->withHeader('Authorization', "Bearer invalidToken")
        ->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
            'status' => 'completed',
        ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should not update order travel of another user', function () {
    login();
    $orderTravel = createOrderTravel(2);

    $response = $this->put("/api/order-travel/update-status/{$orderTravel->uuid}", [
        'status' => 'completed',
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Order travel not found']);
});
