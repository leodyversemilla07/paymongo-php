<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ForexServiceTest extends TestCase
{
    public function testCreateQuoteBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'qt_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'currency' => 'USD',
                    'converted_amount' => 56000,
                    'converted_currency' => 'PHP',
                    'provider' => 'test',
                    'rate_id' => 'rate_test_123',
                    'livemode' => false,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->quotes->create([
            'amount' => 1000,
            'currency' => 'USD',
            'converted_currency' => 'PHP'
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/quotes', $fake->lastRequest['url']);
        $this->assertSame(1000, $fake->lastRequest['params']['amount']);
    }
}
