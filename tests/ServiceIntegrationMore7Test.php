<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMore7Test extends TestCase
{
    public function testPolicyEvaluateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'pe_test_123',
                'attributes' => [
                    'decision' => 'approve',
                    'livemode' => false,
                    'description' => null,
                    'metadata' => null,
                    'created_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $evaluation = $client->policies->evaluate([
            'policy_ids' => ['policy_test_123'],
            'event' => ['type' => 'payment.created'],
        ]);

        $this->assertSame('pe_test_123', $evaluation->id);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/policies/evaluate', $fake->lastRequest['url']);
    }

    public function testWorkflowListHydratesEntities(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
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
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $listing = $client->workflows->all();

        $this->assertFalse($listing->has_more);
        $this->assertCount(1, $listing->data);
        $this->assertSame('wf_test_123', $listing->data[0]->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/workflows', $fake->lastRequest['url']);
    }

    public function testLedgerEntryUpdateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'le_test_123',
                'attributes' => [
                    'account_id' => 'la_test_123',
                    'amount' => 1000,
                    'currency' => 'PHP',
                    'entry_type' => 'credit',
                    'livemode' => false,
                    'reference_number' => null,
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $entry = $client->ledgerEntries->update('le_test_123', [
            'metadata' => ['source' => 'test'],
        ]);

        $this->assertSame('le_test_123', $entry->id);
        $this->assertSame('PATCH', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledger_entries/le_test_123', $fake->lastRequest['url']);
    }
}
