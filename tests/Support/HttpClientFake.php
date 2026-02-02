<?php

declare(strict_types=1);

namespace Paymongo\Tests\Support;

use Paymongo\HttpClient;
use Paymongo\ApiResource;

/**
 * Fake HTTP client for testing purposes.
 */
class HttpClientFake extends HttpClient
{
    /** @var array<string, mixed>|null */
    public ?array $lastRequest = null;
    
    /** @var array<ApiResource> */
    public array $queue = [];
    
    public int $normalizeCallCount = 0;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $apiKey = '';
        if (is_array($config) && array_key_exists('api_key', $config)) {
            $apiKey = $config['api_key'];
        }

        parent::__construct($apiKey, $config);
    }

    /**
     * Queue a response to be returned by the next request.
     */
    public function queueResponse(ApiResource $response): void
    {
        $this->queue[] = $response;
    }

    /**
     * @param array<string, mixed> $opts
     */
    public function request(array $opts): ApiResource
    {
        if (array_key_exists('url', $opts)) {
            $opts['url'] = $this->normalizeUrl($opts['url']);
        }
        $this->lastRequest = $opts;

        if (count($this->queue) === 0) {
            return new ApiResource([]);
        }

        return array_shift($this->queue);
    }

    protected function normalizeUrl(string $url): string
    {
        $this->normalizeCallCount++;
        return parent::normalizeUrl($url);
    }
}
