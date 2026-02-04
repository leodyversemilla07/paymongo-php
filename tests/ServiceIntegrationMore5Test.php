<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMore5Test extends TestCase
{
    public function testLedgerTransactionCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'lt_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'description' => 'Ledger Transaction',
                    'entry_type' => 'credit',
                    'entry_ids' => [],
                    'livemode' => false,
                    'metadata' => null,
                    'reference_number' => null,
                    'status' => 'posted',
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $transaction = $client->ledgerTransactions->create([
            'amount' => 1000,
            'currency' => 'PHP',
        ]);

        $this->assertSame('lt_test_123', $transaction->id);
        $this->assertSame(1000, $transaction->amount);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_transactions', $fake->lastRequest['url']);
    }

    public function testWebhookListHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'wh_test_123',
                    'attributes' => [
                        'livemode' => false,
                        'secret_key' => 'whsec_test',
                        'events' => ['payment.paid'],
                        'url' => 'https://example.com/webhook',
                        'status' => 'enabled',
                        'metadata' => null,
                        'created_at' => 0,
                        'updated_at' => 0,
                    ],
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $listing = $client->webhooks->all();

        $this->assertFalse($listing->has_more);
        $this->assertCount(1, $listing->data);
        $this->assertSame('wh_test_123', $listing->data[0]->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks', $fake->lastRequest['url']);
    }

    public function testWebhookUpdateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'wh_test_123',
                'attributes' => [
                    'livemode' => false,
                    'secret_key' => 'whsec_test',
                    'events' => ['payment.failed'],
                    'url' => 'https://example.com/webhook',
                    'status' => 'enabled',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $webhook = $client->webhooks->update('wh_test_123', [
            'events' => ['payment.failed'],
        ]);

        $this->assertSame('wh_test_123', $webhook->id);
        $this->assertSame('PUT', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/webhooks/wh_test_123', $fake->lastRequest['url']);
    }

    public function testWorkflowExecuteHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'wf_test_123',
                'attributes' => [
                    'description' => 'Test workflow',
                    'livemode' => false,
                    'name' => 'Workflow',
                    'status' => 'active',
                    'steps' => [],
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $workflow = $client->workflows->execute('wf_test_123', [
            'trigger_id' => 'trg_test_123',
        ]);

        $this->assertSame('wf_test_123', $workflow->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/workflows/wf_test_123/execute', $fake->lastRequest['url']);
    }
}
