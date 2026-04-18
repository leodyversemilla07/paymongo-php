<?php

declare(strict_types=1);

namespace Paymongo;

use Paymongo\Exceptions\AuthenticationException;
use Paymongo\Exceptions\InvalidRequestException;
use Paymongo\Exceptions\ResourceNotFoundException;
use Paymongo\Exceptions\RouteNotFoundException;
use Paymongo\Exceptions\BaseException;
use JsonException;

/**
 * HTTP client for making requests to the PayMongo API.
 */
class HttpClient
{
    public const DEFAULT_CONNECTTIMEOUT = 30;
    public const DEFAULT_TIMEOUT = 30;

    protected string $apiKey;
    
    /** @var array<string, mixed> */
    protected array $config = [];

    /**
     * @param string $apiKey The API key for authentication
     * @param array<string, mixed> $config Configuration options
     */
    public function __construct(string $apiKey = '', array $config = [])
    {
        $this->apiKey = $apiKey;
        $this->config = $config;
    }

    /**
     * Make an HTTP request to the API.
     *
     * @param array<string, mixed> $opts Request options
     * @return ApiResource
     * @throws AuthenticationException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     * @throws RouteNotFoundException
     * @throws BaseException
     */
    public function request(array $opts): ApiResource
    {
        $url = $this->buildRequestUrl($opts);

        $ch = curl_init($url);

        $connectTimeout = array_key_exists('connect_timeout', $this->config) && $this->config['connect_timeout'] !== null
            ? $this->config['connect_timeout']
            : self::DEFAULT_CONNECTTIMEOUT;
        $timeout = array_key_exists('timeout', $this->config) && $this->config['timeout'] !== null
            ? $this->config['timeout']
            : self::DEFAULT_TIMEOUT;

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        $headers = $this->buildHeaders($opts);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (in_array($opts['method'], ['DELETE', 'PATCH', 'POST', 'PUT'])) {
            $requestBody = $this->buildRequestBody($opts);
            if ($requestBody !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
            }

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $opts['method']);

            if ($opts['method'] === 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
        }

        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

        if ($body === false) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            $this->throwCurlFailure($error, $errno);
        }

        curl_close($ch);

        if ($code < 200 || $code >= 400) {
            $this->handleErrorResponse((string) $body, $code, $effectiveUrl);
        }

        $jsonBody = $this->decodeJson((string) $body);

        return new ApiResource($jsonBody);
    }

    /**
     * Handle error responses from the API.
     *
     * @throws AuthenticationException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     * @throws RouteNotFoundException
     * @throws BaseException
     */
    private function handleErrorResponse(string $body, int $code, string $url): never
    {
        throw $this->buildErrorException($body, $code, $url);
    }

    /**
     * Normalize URL by removing duplicate slashes.
     */
    protected function normalizeUrl(string $url): string
    {
        return preg_replace('#(?<!:)//+#', '/', $url);
    }

    /**
     * Build the final request URL including GET query parameters.
     *
     * @param array<string, mixed> $opts
     */
    protected function buildRequestUrl(array $opts): string
    {
        $url = $this->normalizeUrl($opts['url']);

        if (isset($opts['params']) && $opts['method'] === 'GET') {
            $url .= '?' . http_build_query($opts['params']);
        }

        return $url;
    }

    /**
     * Build HTTP headers for a request.
     *
     * @param array<string, mixed> $opts
     * @return array<int, string>
     */
    protected function buildHeaders(array $opts): array
    {
        $headers = [];
        
        if (!empty($this->config['http_headers'])) {
            $headers = array_merge($headers, $this->config['http_headers']);
        }

        if (!empty($opts['headers'])) {
            $headers = array_merge($headers, $opts['headers']);
        }

        if (!$this->hasHeader($headers, 'Authorization')) {
            $headers[] = 'Authorization: Basic ' . base64_encode($this->apiKey . ':');
        }

        if (!$this->hasHeader($headers, 'Content-Type')) {
            $headers[] = 'Content-Type:application/json';
        }

        if (array_key_exists('idempotency_key', $opts)) {
            $headers[] = 'Idempotency-Key: ' . $opts['idempotency_key'];
        } elseif (!empty($this->config['idempotency_key'])) {
            $headers[] = 'Idempotency-Key: ' . $this->config['idempotency_key'];
        }

        return $headers;
    }

    /**
     * Check whether a header list already contains a header name.
     *
     * @param array<int, string> $headers
     */
    protected function hasHeader(array $headers, string $name): bool
    {
        foreach ($headers as $header) {
            if (str_starts_with(strtolower($header), strtolower($name) . ':')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build the request body for non-GET requests.
     *
     * @param array<string, mixed> $opts
     */
    protected function buildRequestBody(array $opts): ?string
    {
        if (array_key_exists('body', $opts)) {
            return $opts['body'];
        }

        if (!isset($opts['params'])) {
            return null;
        }

        $data = [
            'data' => [
                'attributes' => $opts['params']
            ]
        ];

        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new BaseException([
                'errors' => [[
                    'detail' => 'Failed to encode request body: ' . $e->getMessage(),
                ]],
            ]);
        }
    }

    /**
     * Build an exception for an error response.
     */
    protected function buildErrorException(string $body, int $code, string $url): \Exception
    {
        $jsonBody = json_decode($body, true);

        if (!is_array($jsonBody)) {
            $jsonBody = [
                'errors' => [[
                    'detail' => $body !== '' ? $body : "HTTP {$code} returned an empty response.",
                ]],
            ];
        }

        return match ($code) {
            401 => new AuthenticationException($jsonBody),
            400 => new InvalidRequestException($jsonBody),
            404 => $body !== ''
                ? new ResourceNotFoundException($jsonBody)
                : new RouteNotFoundException("Route {$url} not found."),
            default => new BaseException($jsonBody),
        };
    }

    /**
     * Decode JSON response body.
     *
     * @return array<string, mixed>
     * @throws BaseException
     */
    protected function decodeJson(string $body): array
    {
        if ($body === '') {
            return [];
        }

        try {
            $jsonBody = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new BaseException([
                'errors' => [[
                    'detail' => 'Invalid JSON response: ' . $e->getMessage(),
                ]],
            ]);
        }

        return is_array($jsonBody) ? $jsonBody : [];
    }

    /**
     * Throw a BaseException for a cURL failure.
     *
     * @throws BaseException
     */
    protected function throwCurlFailure(string $error, int $errno): never
    {
        throw new BaseException([
            'errors' => [[
                'detail' => "cURL error {$errno}: {$error}",
            ]],
        ]);
    }
}
