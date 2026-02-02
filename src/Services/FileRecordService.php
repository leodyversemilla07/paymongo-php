<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\FileRecord;
use Paymongo\Entities\Listing;

/**
 * Service for managing PayMongo File Records.
 */
class FileRecordService extends BaseService
{
    private const URI = '/file_records';

    /**
     * List file records for a child merchant.
     *
     * @param array<string, mixed> $params
     */
    public function listChildMerchant(string $id, array $params = []): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl("/child_merchants/{$id}/file_records"),
            'params' => $params
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new FileRecord($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Create a file record for a child merchant.
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed> $files
     */
    public function createChildMerchant(string $id, array $params, array $files = []): FileRecord
    {
        $body = $this->buildMultipartBody($params, $files);

        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl("/child_merchants/{$id}/file_records"),
            'body'   => $body['body'],
            'headers' => $body['headers'],
            'content_type' => $body['content_type']
        ]);

        return new FileRecord($apiResource);
    }

    /**
     * Delete a file record from a child merchant.
     */
    public function deleteChildMerchant(string $id, string $fileRecordId): FileRecord
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl("/child_merchants/{$id}/file_records/{$fileRecordId}"),
        ]);

        return new FileRecord($apiResource);
    }

    /**
     * List file records for a consumer.
     *
     * @param array<string, mixed> $params
     */
    public function listConsumer(string $id, array $params = []): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl("/consumers/{$id}/file_records"),
            'params' => $params
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new FileRecord($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Create a file record for a consumer.
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed> $files
     */
    public function createConsumer(string $id, array $params, array $files = []): FileRecord
    {
        $body = $this->buildMultipartBody($params, $files);

        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl("/consumers/{$id}/file_records"),
            'body'   => $body['body'],
            'headers' => $body['headers'],
            'content_type' => $body['content_type']
        ]);

        return new FileRecord($apiResource);
    }

    /**
     * Delete a file record from a consumer.
     */
    public function deleteConsumer(string $id, string $fileRecordId): FileRecord
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl("/consumers/{$id}/file_records/{$fileRecordId}"),
        ]);

        return new FileRecord($apiResource);
    }

    /**
     * Build multipart body for file uploads.
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed> $files
     * @return array<string, mixed>
     */
    private function buildMultipartBody(array $params, array $files): array
    {
        $boundary = '----PaymongoBoundary' . md5(uniqid('', true));
        $body = '';

        foreach ($params as $name => $value) {
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Disposition: form-data; name=\"{$name}\"\r\n\r\n";
            $body .= $value . "\r\n";
        }

        foreach ($files as $name => $file) {
            $filename = $file['filename'];
            $contentType = $file['content_type'];
            $contents = $file['contents'];

            $body .= "--{$boundary}\r\n";
            $body .= "Content-Disposition: form-data; name=\"{$name}\"; filename=\"{$filename}\"\r\n";
            $body .= "Content-Type: {$contentType}\r\n\r\n";
            $body .= $contents . "\r\n";
        }

        $body .= "--{$boundary}--\r\n";

        return [
            'body' => $body,
            'headers' => [
                'Content-Type: multipart/form-data; boundary=' . $boundary
            ],
            'content_type' => 'multipart/form-data; boundary=' . $boundary
        ];
    }
}
