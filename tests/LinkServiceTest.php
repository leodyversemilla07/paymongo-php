<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LinkServiceTest extends TestCase
{
    public function testListLinksBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'link_test_123',
                    'attributes' => $this->linkAttributes(false),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $links = $client->links->all(['limit' => 10]);

        $this->assertCount(1, $links->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/links', $fake->lastRequest['url']);
        $this->assertSame(10, $fake->lastRequest['params']['limit']);
    }

    public function testRetrieveLinkBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->linkResource('link_test_123', false));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $link = $client->links->retrieve('link_test_123');

        $this->assertSame('link_test_123', $link->id);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/links/link_test_123', $fake->lastRequest['url']);
    }

    public function testGetLinkByReferenceNumberBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->linkResource('link_test_123', false));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $link = $client->links->getByReferenceNumber('ref_123');

        $this->assertSame('ref_123', $link->reference_number);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/links/reference_number/ref_123', $fake->lastRequest['url']);
    }

    public function testCreateLinkBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->linkResource('link_test_123', false));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $client->links->create([
            'amount' => 1000,
            'currency' => 'PHP',
            'description' => 'Test link',
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/links', $fake->lastRequest['url']);
        $this->assertSame(1000, $fake->lastRequest['params']['amount']);
    }

    public function testArchiveLinkBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->linkResource('link_test_123', true));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $link = $client->links->archive('link_test_123');

        $this->assertTrue($link->archived);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/links/link_test_123/archive', $fake->lastRequest['url']);
    }

    public function testUnarchiveLinkBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);
        $fake->queueResponse($this->linkResource('link_test_123', false));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $link = $client->links->unarchive('link_test_123');

        $this->assertFalse($link->archived);
        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/links/link_test_123/unarchive', $fake->lastRequest['url']);
    }

    /**
     * @return array<string, mixed>
     */
    private function linkAttributes(bool $archived): array
    {
        return [
            'amount' => 1000,
            'archived' => $archived,
            'checkout_url' => 'https://pm.link/test',
            'currency' => 'PHP',
            'description' => 'Test link',
            'fee' => 0,
            'livemode' => false,
            'remarks' => null,
            'reference_number' => 'ref_123',
            'status' => 'unpaid',
            'tax_amount' => null,
            'payments' => [],
            'taxes' => null,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function linkResource(string $id, bool $archived): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->linkAttributes($archived),
            ],
        ]);
    }
}
