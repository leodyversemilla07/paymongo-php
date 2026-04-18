<?php

declare(strict_types=1);

use Paymongo\ApiResource;
use Paymongo\PaymongoClient;
use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class FileRecordServiceTest extends TestCase
{
    public function testListChildMerchantFileRecordsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'file_test_123',
                'attributes' => $this->fileRecordAttributes('test.txt'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $records = $client->fileRecords->listChildMerchant('cm_test_123', ['limit' => 5]);

        $this->assertCount(1, $records->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children/cm_test_123/documents', $fake->lastRequest['url']);
    }

    public function testCreateChildMerchantFileRecordBuildsMultipartRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->fileRecordResource('file_test_123', 'test.txt'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->fileRecords->createChildMerchant('cm_test_123', ['purpose' => 'verification'], [
            'file' => [
                'filename' => 'test.txt',
                'content_type' => 'text/plain',
                'contents' => 'hello',
            ],
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children/cm_test_123/documents', $fake->lastRequest['url']);
        $this->assertStringContainsString('multipart/form-data', $fake->lastRequest['content_type']);
    }

    public function testDeleteChildMerchantFileRecordBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->fileRecordResource('file_test_123', 'test.txt'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $record = $client->fileRecords->deleteChildMerchant('cm_test_123', 'file_test_123');

        $this->assertSame('file_test_123', $record->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/merchants/children/cm_test_123/documents/file_test_123', $fake->lastRequest['url']);
    }

    public function testListConsumerFileRecordsBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse(new ApiResource([
            'data' => [[
                'id' => 'file_test_123',
                'attributes' => $this->fileRecordAttributes('id.png'),
            ]],
            'has_more' => false,
        ]));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $records = $client->fileRecords->listConsumer('cons_test_123');

        $this->assertCount(1, $records->data);
        $this->assertSame('GET', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/consumers/cons_test_123/documents', $fake->lastRequest['url']);
    }

    public function testCreateConsumerFileRecordBuildsMultipartRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->fileRecordResource('file_test_124', 'id.png'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $client->fileRecords->createConsumer('cons_test_123', ['purpose' => 'verification'], [
            'file' => [
                'filename' => 'id.png',
                'content_type' => 'image/png',
                'contents' => 'png-data',
            ],
        ]);

        $this->assertSame('POST', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/consumers/cons_test_123/documents', $fake->lastRequest['url']);
    }

    public function testDeleteConsumerFileRecordBuildsRequest(): void
    {
        $fake = new HttpClientFake(['api_key' => 'sk_test_key']);
        $fake->queueResponse($this->fileRecordResource('file_test_124', 'id.png'));

        $client = new PaymongoClient('sk_test_key', ['http_client' => $fake]);

        $record = $client->fileRecords->deleteConsumer('cons_test_123', 'file_test_124');

        $this->assertSame('file_test_124', $record->id);
        $this->assertSame('DELETE', $fake->lastRequest['method']);
        $this->assertSame('https://api.paymongo.com/v1/consumers/cons_test_123/documents/file_test_124', $fake->lastRequest['url']);
    }

    /** @return array<string, mixed> */
    private function fileRecordAttributes(string $filename): array
    {
        return [
            'filename' => $filename,
            'livemode' => false,
            'purpose' => 'verification',
            'status' => 'uploaded',
            'url' => 'https://example.com/file',
            'metadata' => null,
            'created_at' => 0,
            'updated_at' => 0,
        ];
    }

    private function fileRecordResource(string $id, string $filename): ApiResource
    {
        return new ApiResource([
            'data' => [
                'id' => $id,
                'attributes' => $this->fileRecordAttributes($filename),
            ],
        ]);
    }
}
