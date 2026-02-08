<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('blocks buyers from seller portal', function () {
    $buyer = User::factory()->create([
        'role' => 'buyer',
        'seller_status' => 'pending',
    ]);

    $response = $this->actingAs($buyer)->get('/seller/dashboard');

    $response->assertStatus(403);
});

it('allows sellers into seller portal', function () {
    $seller = User::factory()->create([
        'role' => 'seller',
        'seller_status' => 'approved',
    ]);

    $response = $this->actingAs($seller)->get('/seller/dashboard');

    $response->assertOk();
});

it('blocks unauthenticated checkout', function () {
    $response = $this->postJson('/order/place', []);

    $response->assertStatus(401);
});
