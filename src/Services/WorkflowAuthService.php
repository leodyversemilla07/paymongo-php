<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\Entities\WorkflowAuthToken;

/**
 * Service for managing PayMongo Workflow Authentication.
 */
class WorkflowAuthService extends BaseService
{
    private const URI = '/auth/tokens';

    /**
     * Issue a new workflow auth token.
     *
     * @param array<string, mixed> $params
     */
    public function issue(array $params): WorkflowAuthToken
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new WorkflowAuthToken($apiResource);
    }
}
