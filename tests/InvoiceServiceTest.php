<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class InvoiceServiceTest extends TestCase
{
    public function testPayInvoiceBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'inv_test_123',
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
                    'status' => 'open',
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->invoices->pay('inv_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/invoices/inv_test_123/pay', $fake->lastRequest['url']);
    }
}
