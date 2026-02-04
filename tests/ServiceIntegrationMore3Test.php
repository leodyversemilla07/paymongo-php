<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ServiceIntegrationMore3Test extends TestCase
{
    public function testWorkflowCreateHydratesEntity(): void
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

        $workflow = $client->workflows->create([
            'name' => 'Workflow',
            'steps' => [],
        ]);

        $this->assertSame('wf_test_123', $workflow->id);
        $this->assertSame('Workflow', $workflow->name);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/workflows', $fake->lastRequest['url']);
    }

    public function testPolicyCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
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
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $policy = $client->policies->create([
            'name' => 'Policy',
            'rules' => [],
        ]);

        $this->assertSame('policy_test_123', $policy->id);
        $this->assertSame('Policy', $policy->name);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/policies', $fake->lastRequest['url']);
    }

    public function testLedgerCreateHydratesEntity(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'ledger_test_123',
                'attributes' => [
                    'currency' => 'PHP',
                    'description' => 'Ledger',
                    'livemode' => false,
                    'name' => 'Ledger',
                    'status' => 'active',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0,
                ],
            ],
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $ledger = $client->ledgers->create([
            'currency' => 'PHP',
            'name' => 'Ledger',
        ]);

        $this->assertSame('ledger_test_123', $ledger->id);
        $this->assertSame('Ledger', $ledger->name);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/ledgers', $fake->lastRequest['url']);
    }
}
