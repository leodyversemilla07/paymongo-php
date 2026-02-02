<?php

declare(strict_types=1);

use Paymongo\PaymongoClient;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class PaymongoClientTest extends TestCase
{
    public function testClientDefaultsCanBeOverridden(): void
    {
        $client = new PaymongoClient('sk_test_key', [
            'api_base_url' => 'https://api.example.com',
            'api_version' => 'v9',
            'timeout' => 5,
            'connect_timeout' => 3,
            'idempotency_key' => 'idem_123',
            'http_headers' => ['X-Test: true'],
        ]);

        $this->assertSame('https://api.example.com', $client->apiBaseUrl);
        $this->assertSame('v9', $client->apiVersion);
        $this->assertSame('sk_test_key', $client->apiKey);
        $this->assertSame('idem_123', $client->config['idempotency_key']);
        $this->assertSame(5, $client->config['timeout']);
        $this->assertSame(3, $client->config['connect_timeout']);
        $this->assertSame(['X-Test: true'], $client->config['http_headers']);
    }
}
