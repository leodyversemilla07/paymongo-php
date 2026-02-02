<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class WorkflowServiceTest extends TestCase
{
    public function testCreateWorkflowBuildsRequest(): void
    {
        $fake = new \Paymongo\Tests\Support\HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new \Paymongo\ApiResource([
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
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->workflows->create([
            'name' => 'Workflow',
            'description' => 'Test workflow'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/workflows', $fake->lastRequest['url']);
        $this->assertSame('Workflow', $fake->lastRequest['params']['name']);
    }
}
