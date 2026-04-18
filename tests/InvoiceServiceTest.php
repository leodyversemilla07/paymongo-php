<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class InvoiceServiceTest extends TestCase
{
    public function testRetrieveInvoiceBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->invoiceResource('inv_test_123', 'open'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $invoice = $client->invoices->retrieve('inv_test_123');

        $this->assertSame('inv_test_123', $invoice->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/invoices/inv_test_123', $fake->lastRequest['url']);
    }

    public function testListInvoicesBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'inv_test_123',
                'attributes' => $this->invoiceAttributes('open'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $invoices = $client->invoices->all(['limit' => 10]);

        $this->assertCount(1, $invoices->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/invoices', $fake->lastRequest['url']);
    }

    public function testCreateInvoiceLineItemBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->invoiceResource('inv_test_123', 'open'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->invoices->createLineItem('inv_test_123', ['amount' => 500, 'description' => 'Item']);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/invoices/inv_test_123/line_items', $fake->lastRequest['url']);
        $this->assertSame(500, $fake->lastRequest['params']['amount']);
    }

    public function testPayInvoiceBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->invoiceResource('inv_test_123', 'open'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->invoices->pay('inv_test_123');

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/invoices/inv_test_123/pay', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function invoiceAttributes(string $status): array
    {
        return [
            'amount' => 1000,
            'amount_due' => 1000,
            'billing' => null,
            'currency' => 'PHP',
            'due_date' => null,
            'livemode' => false,
            'line_items' => [],
            'metadata' => null,
            'paid_at' => null,
            'status' => $status,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function invoiceResource(string $id, string $status): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->invoiceAttributes($status),
            ],
        ]);
    }
}
