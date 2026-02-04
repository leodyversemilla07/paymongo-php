<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMore4Test extends TestCase
{
    public function testWalletRetrieveHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'wallet_test_123',
                'attributes' => [
                    'available_balance' => 5000,
                    'balance' => 7000,
                    'currency' => 'PHP',
                    'livemode' => false,
                    'name' => 'Main Wallet',
                    'type' => 'merchant',
                    'status' => 'active',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $wallet = $client->wallets->retrieve('wallet_test_123');

        $this->assertSame('wallet_test_123', $wallet->id);
        $this->assertSame('Main Wallet', $wallet->name);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/wallets/wallet_test_123', $fake->lastRequest['url']);
    }

    public function testTransferCreateBatchHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'tr_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'description' => 'Transfer',
                    'livemode' => false,
                    'reference_number' => null,
                    'status' => 'pending',
                    'recipient' => null,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $transfer = $client->transfers->createBatch([
            'amount' => 1000,
            'currency' => 'PHP',
        ]);

        $this->assertSame('tr_test_123', $transfer->id);
        $this->assertSame(1000, $transfer->amount);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/transfers', $fake->lastRequest['url']);
    }

    public function testInvoiceRetrieveHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'in_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'amount_due' => 1000,
                    'billing' => null,
                    'currency' => 'PHP',
                    'due_date' => null,
                    'livemode' => false,
                    'line_items' => [],
                    'metadata' => null,
                    'paid_at' => null,
                    'status' => 'draft',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $invoice = $client->invoices->retrieve('in_test_123');

        $this->assertSame('in_test_123', $invoice->id);
        $this->assertSame(1000, $invoice->amount);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/invoices/in_test_123', $fake->lastRequest['url']);
    }
}
