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
    
    /** @var array<ApiResource|\Throwable> */
    public array $queue = [];
    
    public int $normalizeCallCount = 0;
    
    /** @var array<string, mixed>|null */
    public ?array $lastDecodedBody = null;

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

        $response = array_shift($this->queue);

        if ($response instanceof \Throwable) {
            throw $response;
        }

        if ($response instanceof ApiResource) {
            $this->lastDecodedBody = ['data' => $response->data];
            return $response;
        }

        return new ApiResource([]);
    }

    protected function normalizeUrl(string $url): string
    {
        $this->normalizeCallCount++;
        return parent::normalizeUrl($url);
    }

    /**
     * Expose JSON decoding for tests.
     *
     * @return array<string, mixed>
     */
    public function decodePublic(string $body): array
    {
        return $this->decodeJson($body);
    }
}
