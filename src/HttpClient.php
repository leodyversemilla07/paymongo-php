<?php

declare(strict_types=1);

namespace Paymongo;

use Paymongo\Exceptions\AuthenticationException;
use Paymongo\Exceptions\InvalidRequestException;
use Paymongo\Exceptions\ResourceNotFoundException;
use Paymongo\Exceptions\RouteNotFoundException;
use Paymongo\Exceptions\BaseException;

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
        $url = $opts['url'];
        $url = $this->normalizeUrl($url);

        if (isset($opts['params']) && $opts['method'] === 'GET') {
            $url .= '?' . http_build_query($opts['params']);
        }

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
        
        $headers = [
            'Content-Type:application/json',
            'Authorization: Basic ' . base64_encode($this->apiKey . ':'),
        ];
        
        if (!empty($this->config['http_headers'])) {
            $headers = array_merge($headers, $this->config['http_headers']);
        }

        if (array_key_exists('idempotency_key', $opts)) {
            $headers[] = 'Idempotency-Key: ' . $opts['idempotency_key'];
        } elseif (!empty($this->config['idempotency_key'])) {
            $headers[] = 'Idempotency-Key: ' . $this->config['idempotency_key'];
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (in_array($opts['method'], ['DELETE', 'POST', 'PUT'])) {
            if (isset($opts['params'])) {
                $data = [
                    'data' => [
                        'attributes' => $opts['params']
                    ]
                ];
                $dataString = json_encode($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            }

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $opts['method']);
            curl_setopt($ch, CURLOPT_POST, 1);
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
        $jsonBody = json_decode($body, true);

        $exception = match ($code) {
            401 => new AuthenticationException($jsonBody),
            400 => new InvalidRequestException($jsonBody),
            404 => !empty($body)
                ? new ResourceNotFoundException($jsonBody)
                : new RouteNotFoundException("Route {$url} not found."),
            default => new BaseException($jsonBody),
        };

        throw $exception;
    }

    /**
     * Normalize URL by removing duplicate slashes.
     */
    protected function normalizeUrl(string $url): string
    {
        return preg_replace('#(?<!:)//+#', '/', $url);
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

        $jsonBody = json_decode($body, true);
        if ($jsonBody === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new BaseException([
                'errors' => [[
                    'detail' => 'Invalid JSON response: ' . json_last_error_msg(),
                ]],
            ]);
        }

        return $jsonBody ?? [];
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
