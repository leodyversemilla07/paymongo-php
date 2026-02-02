<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\QrPh;

/**
 * Service for managing PayMongo QR PH.
 */
class QrPhService extends BaseService
{
    private const URI = '/qrph';

    /**
     * Create a static QR code.
     *
     * @param array<string, mixed> $params
     */
    public function createStatic(array $params): QrPh
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new QrPh($apiResource);
    }
}
