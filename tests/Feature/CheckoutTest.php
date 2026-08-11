<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private string $apiKey = 'secret-key-demo';

    private function validPayload(): array
    {
        return [
            'merchant_order_id' => 'ORDER-TEST-' . uniqid(),
            'amount' => 150000,
            'currency' => 'IDR',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'callback_url' => 'https://example.com/callback',
            'return_url' => 'https://example.com/return',
        ];
    }

    public function test_checkout_can_be_created(): void
    {
        $payload = $this->validPayload();

        $response = $this
            ->withHeader('X-API-Key', $this->apiKey)
            ->postJson('/api/checkout', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'transaction_id',
                'checkout_url',
                'status',
            ])
            ->assertJson([
                'status' => 'PENDING',
            ]);

        $this->assertDatabaseHas('transactions', [
            'merchant_order_id' => $payload['merchant_order_id'],
            'status' => 'PENDING',
        ]);
    }

    public function test_checkout_requires_api_key(): void
    {
        $response = $this->postJson(
            '/api/checkout',
            $this->validPayload()
        );

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid or missing API key.',
            ]);
    }

    public function test_checkout_rejects_duplicate_order_id(): void
    {
        $payload = $this->validPayload();

        $this
            ->withHeader('X-API-Key', $this->apiKey)
            ->postJson('/api/checkout', $payload)
            ->assertStatus(201);

        $response = $this
            ->withHeader('X-API-Key', $this->apiKey)
            ->postJson('/api/checkout', $payload);

        $response->assertStatus(422);
    }

    public function test_checkout_rejects_invalid_amount(): void
    {
        $payload = $this->validPayload();
        $payload['amount'] = 0;

        $response = $this
            ->withHeader('X-API-Key', $this->apiKey)
            ->postJson('/api/checkout', $payload);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['amount']);
    }
    public function test_transaction_not_found_returns_404(): void
    {
        $response = $this
            ->withHeader('X-API-Key', $this->apiKey)
            ->getJson('/api/transactions/TRX-NOT-FOUND');

        $response
            ->assertStatus(404)
            ->assertJson([
                'message' => 'Transaction not found.',
            ]);
    }
}