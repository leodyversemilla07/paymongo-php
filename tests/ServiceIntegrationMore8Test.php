<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMore8Test extends TestCase
{
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

    public function testPolicyListHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'policy_test_123',
                    'attributes' => [
                        'description' => 'Test policy',
                        'livemode' => false,
                        'name' => 'Policy',
                        'rules' => [],
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

        $listing = $client->policies->all();

        $this->assertFalse($listing->has_more);
        $this->assertCount(1, $listing->data);
        $this->assertSame('policy_test_123', $listing->data[0]->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/policies', $fake->lastRequest['url']);
    }

    public function testLedgerTransactionReverseHydratesEntity(): void
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
                    'livemode' => false,
                    'reference_number' => null,
                    'status' => 'reversed',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $transaction = $client->ledgerTransactions->reverse('lt_test_123');

        $this->assertSame('lt_test_123', $transaction->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_transactions/lt_test_123/reverse', $fake->lastRequest['url']);
    }
}
