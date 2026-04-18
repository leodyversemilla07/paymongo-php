<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class ProductServiceTest extends TestCase
{
    public function testRetrieveProductBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse($this->productResource('prod_test_123', 'T-Shirt'));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $product = $client->products->retrieve('prod_test_123');

        $this->assertSame('prod_test_123', $product->id);
        $this->assertSame('T-Shirt', $product->name);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/products/prod_test_123', $fake->lastRequest['url']);
    }

    public function testListProductsBuildsRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key',
        ]);

        $fake->queueResponse(new ApiResource([
            'data' => [
                [
                    'id' => 'prod_test_123',
                    'attributes' => $this->productAttributes('T-Shirt'),
                ],
            ],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake,
        ]);

        $products = $client->products->all(['limit' => 5]);

        $this->assertCount(1, $products->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/products', $fake->lastRequest['url']);
        $this->assertSame(5, $fake->lastRequest['params']['limit']);
    }

    /**
     * @return array<string, mixed>
     */
    private function productAttributes(string $name): array
    {
        return [
            'name' => $name,
            'description' => 'Test product',
            'images' => [],
            'livemode' => false,
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function productResource(string $id, string $name): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->productAttributes($name),
            ],
        ]);
    }
}
