<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\QrTransfer;

/**
 * Service for managing PayMongo QR Transfers.
 */
class QrTransferService extends BaseService
{
    private const URI = '/qr';

    /**
     * Generate a QR transfer.
     *
     * @param array<string, mixed> $params
     */
    public function generate(array $params): QrTransfer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new QrTransfer($apiResource);
    }

    /**
     * Execute a QR transfer.
     *
     * @param array<string, mixed> $params
     */
    public function execute(array $params): QrTransfer
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . '/execute'),
            'params' => $params
        ]);

        return new QrTransfer($apiResource);
    }
}
