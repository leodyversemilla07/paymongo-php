<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Event;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Webhook;
use Paymongo\Exceptions\SignatureVerificationException;
use Paymongo\Exceptions\UnexpectedValueException;

/**
 * Service for managing PayMongo Webhooks.
 */
class WebhookService extends BaseService
{
    private const URI = '/webhooks';

    /**
     * Create a new webhook.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Webhook
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Webhook($apiResource);
    }

    /**
     * Update a webhook.
     *
     * @param array<string, mixed> $params
     */
    public function update(string $id, array $params): Webhook
    {
        $apiResource = $this->httpClient->request([
            'method' => 'PUT',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
            'params' => $params
        ]);

        return new Webhook($apiResource);
    }

    /**
     * Retrieve a webhook by ID.
     */
    public function retrieve(string $id): Webhook
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Webhook($apiResource);
    }

    /**
     * List all webhooks.
     */
    public function all(): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI),
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new Webhook($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Enable a webhook.
     */
    public function enable(string $id): Webhook
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/enable"),
        ]);

        return new Webhook($apiResource);
    }

    /**
     * Disable a webhook.
     */
    public function disable(string $id): Webhook
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/disable"),
        ]);

        return new Webhook($apiResource);
    }

    /**
     * Construct an event from a webhook payload and verify its signature.
     *
     * @param array{payload: string, signature_header: string, webhook_secret_key: string} $opts
     * @throws UnexpectedValueException If the signature format is invalid
     * @throws SignatureVerificationException If the signature verification fails
     */
    public function constructEvent(array $opts): Event
    {
        $payload = $opts['payload'];
        $signatureHeader = $opts['signature_header'];
        $webhookSecretKey = $opts['webhook_secret_key'];

        if (!is_string($signatureHeader)) {
            throw new UnexpectedValueException('Signature must be a string.');
        }

        $signatureParts = $this->parseSignatureHeader($signatureHeader);
        $timestamp = $signatureParts['t'];
        $comparisonSignature = $signatureParts['li'] !== ''
            ? $signatureParts['li']
            : $signatureParts['te'];

        if (!hash_equals(hash_hmac('sha256', $timestamp . '.' . $payload, $webhookSecretKey), $comparisonSignature)) {
            throw new SignatureVerificationException("The signature is invalid.");
        }

        $jsonDecodedBody = json_decode($payload, true);

        $apiResource = new ApiResource($jsonDecodedBody);

        return new Event($apiResource);
    }

    /**
     * Parse a PayMongo webhook signature header into its required parts.
     *
     * @return array{t: string, te: string, li: string}
     * @throws UnexpectedValueException
     */
    private function parseSignatureHeader(string $signatureHeader): array
    {
        $parts = [];

        foreach (explode(',', $signatureHeader) as $segment) {
            $segment = trim($segment);

            if ($segment === '') {
                continue;
            }

            $pair = explode('=', $segment, 2);

            if (count($pair) !== 2 || trim($pair[0]) === '') {
                throw new UnexpectedValueException('The format of the signature is invalid.');
            }

            $parts[trim($pair[0])] = trim($pair[1]);
        }

        if (!array_key_exists('t', $parts) || $parts['t'] === '') {
            throw new UnexpectedValueException('The signature timestamp is missing.');
        }

        if (!array_key_exists('te', $parts) && !array_key_exists('li', $parts)) {
            throw new UnexpectedValueException('The signature is missing.');
        }

        return [
            't' => $parts['t'],
            'te' => $parts['te'] ?? '',
            'li' => $parts['li'] ?? '',
        ];
    }
}
