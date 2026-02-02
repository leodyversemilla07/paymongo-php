<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class WorkflowAuthServiceTest extends TestCase
{
    public function testIssueWorkflowAuthTokenBuildsRequest(): void
    {
        $fake = new \Paymongo\Tests\Support\HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new \Paymongo\ApiResource([
            'data' => [
                'id' => 'token_test_123',
                'attributes' => [
                    'token' => 'token_value',
                    'expires_at' => 0,
                    'livemode' => false,
                    'created_at' => 0
                ]
            ]
        ]));

        $client = new \Paymongo\PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->workflowAuth->issue([
            'scopes' => ['workflows']
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/auth/tokens', $fake->lastRequest['url']);
        $this->assertSame(['workflows'], $fake->lastRequest['params']['scopes']);
    }
}
