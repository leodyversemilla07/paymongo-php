<?php

declare(strict_types=1);

namespace Paymongo\Services;

use Paymongo\PaymongoClient;
use Paymongo\HttpClient;

/**
 * Base service class providing common functionality for all API services.
 */
abstract class BaseService
{
    protected PaymongoClient $client;
    protected HttpClient $httpClient;

    public function __construct(PaymongoClient $client)
    {
        $this->client = $client;

        if (!empty($this->client->config['http_client'])) {
            $this->httpClient = $this->client->config['http_client'];
            return;
        }

        $this->httpClient = new HttpClient($this->client->config['api_key'], $this->client->config);
    }

    /**
     * Build a full URL for an API endpoint.
     *
     * @param string $path The endpoint path (e.g., '/payment_intents')
     * @return string The full URL
     */
    protected function buildUrl(string $path = ''): string
    {
        return $this->client->buildUrl($path);
    }
}
