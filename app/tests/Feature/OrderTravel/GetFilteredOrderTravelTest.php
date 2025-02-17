<?php

use Tests\Support\{OrderTravelStructure};

beforeEach(function (): void {
    $this->token = login();
    $this->withHeader('Authorization', "Bearer {$this->token}");
    $this->withHeader('Accept', "application/json");

    $this->orderTravel = createOrderTravel();
});

it('should not list order travels without filters', function (): void {
    $response = $this->get('/api/order-travel');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_at', 'status']);
});

it('should list order travels with start_at and status', function (): void {
    $filter = [
        'start_at' => $this->orderTravel->start_date,
        'status' => $this->orderTravel->status,
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(200)
        ->assertJsonStructure(OrderTravelStructure::get());
});

it('should not list order travels with invalid status', function (): void {
    $filter = [
        'start_at' => $this->orderTravel->start_date,
        'status' => 'invalidStatus',
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});

it('should not list order travels with invalid start_at', function (): void {
    $filter = [
        'start_at' => 'invalidDate',
        'status' => $this->orderTravel->status,
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_at']);
});

it('should not list order travels with start_at bigger than end_at', function (): void {
    $filter = [
        'start_at' => $this->orderTravel->start_at,
        'end_at' => '2024-04-01',
        'status' => $this->orderTravel->status,
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_at']);
});

it('should list order travels with start_at, end_at and status', function (): void {
    $filter = [
        'start_at' => $this->orderTravel->start_date,
        'end_at' => date('Y-m-d', strtotime($this->orderTravel->start_date . ' + 1 week')),
        'status' => $this->orderTravel->status,
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(200)
        ->assertJsonStructure(OrderTravelStructure::get());
});

it('should list order travels with start_at, end_at, status and destiny', function (): void {
    $filter = [
        'start_at' => $this->orderTravel->start_date,
        'end_at' => date('Y-m-d', strtotime($this->orderTravel->start_date . ' + 1 week')),
        'status' => $this->orderTravel->status,
        'destiny' => $this->orderTravel->destination,
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(200)
        ->assertJsonStructure(OrderTravelStructure::get());
});

it('should not list order travels with invalid destiny', function (): void {
    $filter = [
        'start_at' => $this->orderTravel->start_date,
        'end_at' => date('Y-m-d', strtotime($this->orderTravel->start_date . ' + 1 week')),
        'status' => $this->orderTravel->status,
        'destiny' => 'invalidDestiny',
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(200)
        ->assertJsonStructure(OrderTravelStructure::get());
});

it('should not list order travels with invalid start_at and end_at', function (): void {
    $filter = [
        'start_at' => 'invalidDate',
        'end_at' => 'invalidDate',
        'status' => $this->orderTravel->status,
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_at', 'end_at']);
});

it('should return a list of order travels with a part of destination, start_at and status', function (): void {
    $filter = [
        'start_at' => $this->orderTravel->start_date,
        'status' => $this->orderTravel->status,
        'destiny' => substr((string) $this->orderTravel->destination, 0, 5),
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(200)
        ->assertJsonStructure(OrderTravelStructure::get());
});

it('should return a list of order travels with a part of destination, start_at, end_at and status', function (): void {
    $filter = [
        'start_at' => $this->orderTravel->start_date,
        'end_at' => date('Y-m-d', strtotime($this->orderTravel->start_date . ' + 1 week')),
        'status' => $this->orderTravel->status,
        'destiny' => substr((string) $this->orderTravel->destination, 0, 5),
    ];

    $response = $this->get('/api/order-travel?' . http_build_query($filter));

    $response->assertStatus(200)
        ->assertJsonStructure(OrderTravelStructure::get());
});
