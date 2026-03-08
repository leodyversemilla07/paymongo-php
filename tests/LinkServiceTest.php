<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class LinkServiceTest extends TestCase
{
    public function testGetLinkByReferenceNumberBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'link_test_123',
                'attributes' => [
                    'amount' => 1000,
                    'archived' => false,
                    'checkout_url' => 'https://pm.link/test',
                    'currency' => 'PHP',
                    'description' => 'Test link',
                    'fee' => 0,
                    'livemode' => false,
                    'remarks' => null,
                    'reference_number' => 'ref_123',
                    'status' => 'unpaid',
                    'tax_amount' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->links->getByReferenceNumber('ref_123');

        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/links/reference_number/ref_123', $fake->lastRequest['url']);
    }
}
