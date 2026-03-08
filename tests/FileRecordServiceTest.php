<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class FileRecordServiceTest extends TestCase
{
    public function testCreateChildMerchantFileRecordBuildsMultipartRequest(): void
    {
        $fake = new HttpClientFake([
            'api_key' => 'sk_test_key'
        ]);
        $fake->queueResponse(new ApiResource([
            'data' => [
                'id' => 'file_test_123',
                'attributes' => [
                    'filename' => 'test.txt',
                    'livemode' => false,
                    'purpose' => 'verification',
                    'status' => 'uploaded',
                    'url' => 'https://example.com/file',
                    'metadata' => null,
                    'created_at' => 0,
                    'updated_at' => 0
                ]
            ]
        ]));

        $client = new PaymongoClient('sk_test_key', [
            'http_client' => $fake
        ]);

        $client->fileRecords->createChildMerchant('cm_test_123', [
            'purpose' => 'verification'
        ], [
            'file' => [
                'filename' => 'test.txt',
                'content_type' => 'text/plain',
                'contents' => 'hello'
            ]
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children/cm_test_123/documents', $fake->lastRequest['url']);
        $this->assertArrayNotHasKey('params', $fake->lastRequest);
        $this->assertStringContainsString('Content-Type: multipart/form-data; boundary=', $fake->lastRequest['headers'][0]);
        $this->assertStringContainsString('multipart/form-data', $fake->lastRequest['content_type']);
        $this->assertStringContainsString('name="purpose"', $fake->lastRequest['body']);
        $this->assertStringContainsString('verification', $fake->lastRequest['body']);
        $this->assertStringContainsString('filename="test.txt"', $fake->lastRequest['body']);
        $this->assertStringContainsString('Content-Type: text/plain', $fake->lastRequest['body']);
        $this->assertStringContainsString('hello', $fake->lastRequest['body']);
    }
}
