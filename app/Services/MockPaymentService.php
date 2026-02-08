<?php

namespace App\Services;

use App\Models\User;

class MockPaymentService
{
    /**
     * Simulate a payment charge and return a reference/token.
     */
    public function charge(User $user, float $amount, string $method = 'COD'): array
    {
        // In a real integration this would call a provider and verify signatures/webhooks.
        $reference = strtoupper($method) . '-MOCK-' . now()->timestamp;

        return [
            'reference' => $reference,
            'status' => $method === 'COD' ? 'pending' : 'paid',
        ];
    }
}
