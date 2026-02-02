<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\Challenge;

/**
 * Service for managing PayMongo Challenges.
 */
class ChallengeService extends BaseService
{
    private const URI = '/challenges';

    /**
     * Create a new challenge.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Challenge
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Challenge($apiResource);
    }

    /**
     * Verify a challenge.
     *
     * @param array<string, mixed> $params
     */
    public function verify(string $id, array $params): Challenge
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/verify"),
            'params' => $params
        ]);

        return new Challenge($apiResource);
    }
}
