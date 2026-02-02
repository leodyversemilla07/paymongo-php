<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\ApiResource;
use Paymongo\Entities\Listing;
use Paymongo\Entities\Workflow;

/**
 * Service for managing PayMongo Workflows.
 */
class WorkflowService extends BaseService
{
    private const URI = '/workflows';

    /**
     * Create a new workflow.
     *
     * @param array<string, mixed> $params
     */
    public function create(array $params): Workflow
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        return new Workflow($apiResource);
    }

    /**
     * Retrieve a workflow by ID.
     */
    public function retrieve(string $id): Workflow
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Workflow($apiResource);
    }

    /**
     * List all workflows.
     *
     * @param array<string, mixed> $params
     */
    public function all(array $params = []): Listing
    {
        $apiResource = $this->httpClient->request([
            'method' => 'GET',
            'url'    => $this->buildUrl(self::URI),
            'params' => $params
        ]);

        $objects = [];

        foreach ($apiResource->data as $row) {
            $rowResource = new ApiResource($row);
            $objects[] = new Workflow($rowResource);
        }

        return new Listing([
            'has_more' => $apiResource->hasMore,
            'data'     => $objects,
        ]);
    }

    /**
     * Delete a workflow.
     */
    public function delete(string $id): Workflow
    {
        $apiResource = $this->httpClient->request([
            'method' => 'DELETE',
            'url'    => $this->buildUrl(self::URI . "/{$id}"),
        ]);

        return new Workflow($apiResource);
    }

    /**
     * Execute a workflow.
     *
     * @param array<string, mixed> $params
     */
    public function execute(string $id, array $params = []): Workflow
    {
        $apiResource = $this->httpClient->request([
            'method' => 'POST',
            'url'    => $this->buildUrl(self::URI . "/{$id}/execute"),
            'params' => $params
        ]);

        return new Workflow($apiResource);
    }
}
