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

        $arrSignature = explode(',', $signatureHeader);

        if ($arrSignature === false || count($arrSignature) < 3) {
            throw new UnexpectedValueException('The format of the signature is invalid.');
        }

        $timestamp = explode('=', $arrSignature[0])[1];
        $testModeSignature = explode('=', $arrSignature[1])[1];
        $liveModeSignature = explode('=', $arrSignature[2])[1];

        $comparisonSignature = '';
        
        if (!empty($testModeSignature)) {
            $comparisonSignature = $testModeSignature;
        }

        if (!empty($liveModeSignature)) {
            $comparisonSignature = $liveModeSignature;
        }

        if (!hash_equals(hash_hmac('sha256', $timestamp . '.' . $payload, $webhookSecretKey), $comparisonSignature)) {
            throw new SignatureVerificationException("The signature is invalid.");
        }

        $jsonDecodedBody = json_decode($payload, true);

        $apiResource = new ApiResource($jsonDecodedBody);

        return new Event($apiResource);
    }
}
