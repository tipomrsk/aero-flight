<?php

beforeEach(function () {
    $this->token = login();
    $this->withHeader('Authorization', "Bearer {$this->token}");
    $this->withHeader('Accept', "application/json");

    $this->orderTravel = createOrderTravel();
});

it('should update order travel status', function () {
    $response = $this->put("/api/order-travel/update-status/{$this->orderTravel->uuid}", [
        'status' => 'completed',
    ]);

    dd($response->getContent());

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => $this->orderTravel->origin,
            'destination' => $this->orderTravel->destination,
            'start_date' => $this->orderTravel->start_date,
            'end_date' => $this->orderTravel->end_date,
            'status' => 'completed',
        ]);
});
