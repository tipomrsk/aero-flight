<?php

beforeEach(function () {
    $this->token = login([
        'is_admin' => true,
    ]);
    $this->withHeader('Authorization', "Bearer {$this->token}");
    $this->withHeader('Accept', "application/json");

    $this->orderTravel = createOrderTravel();
});

it('should delete order travel', function () {
    $response = $this->delete("/api/order-travel/{$this->orderTravel->uuid}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Order travel deleted',
        ]);
});

it('should not delete order travel with invalid id', function () {
    $response = $this->delete('/api/order-travel/invalidId');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should not delete order travel without token', function () {
    $response = $this->withHeader('Authorization', 'Bearer invalidToken')
        ->delete("/api/order-travel/{$this->orderTravel->uuid}");

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should not delete if not admin', function () {
    $notAdminToken = login();

    $orderTravel = createOrderTravel([
        'user_id' => 2,
    ]);

    $response = $this->withHeader('Authorization', "Bearer {$notAdminToken}")
        ->delete("/api/order-travel/{$orderTravel->uuid}");

    $response->assertStatus(403)
        ->assertJson(['message' => 'Unauthorized']);
});
