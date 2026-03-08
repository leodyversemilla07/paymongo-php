<?php

declare(strict_types=1);

use Paymongo\Exceptions\BaseException;
use Paymongo\Exceptions\ResourceNotFoundException;
use Paymongo\Exceptions\RouteNotFoundException;
use Paymongo\HttpClient;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class HttpClientTransportTest extends TestCase
{
    private function makeClient(array $config = []): HttpClient
    {
        return new class('sk_test_key', $config) extends HttpClient {
            /**
             * @param array<string, mixed> $opts
             * @return array<int, string>
             */
            public function buildHeadersPublic(array $opts): array
            {
                return $this->buildHeaders($opts);
            }

            /**
             * @param array<string, mixed> $opts
             */
            public function buildBodyPublic(array $opts): ?string
            {
                return $this->buildRequestBody($opts);
            }

            /**
             * @param array<string, mixed> $opts
             */
            public function buildUrlPublic(array $opts): string
            {
                return $this->buildRequestUrl($opts);
            }

            public function buildErrorExceptionPublic(string $body, int $code, string $url): \Exception
            {
                return $this->buildErrorException($body, $code, $url);
            }
        };
    }

    public function testBuildRequestUrlNormalizesAndAppendsQueryString(): void
    {
        $client = $this->makeClient();

        $url = $client->buildUrlPublic([
            'method' => 'GET',
            'url' => 'https://api.paymongo.com//v1//payments',
            'params' => [
                'page' => 2,
                'status' => 'paid',
            ],
        ]);

        $this->assertSame('https://api.paymongo.com/v1/payments?page=2&status=paid', $url);
    }

    public function testBuildHeadersPrefersPerRequestIdempotencyKey(): void
    {
        $client = $this->makeClient([
            'idempotency_key' => 'idem_global',
            'http_headers' => ['X-Global: 1'],
        ]);

        $headers = $client->buildHeadersPublic([
            'method' => 'POST',
            'url' => 'https://api.paymongo.com/v1/payments',
            'idempotency_key' => 'idem_request',
        ]);

        $this->assertContains('Authorization: Basic ' . base64_encode('sk_test_key:'), $headers);
        $this->assertContains('X-Global: 1', $headers);
        $this->assertContains('Idempotency-Key: idem_request', $headers);
        $this->assertNotContains('Idempotency-Key: idem_global', $headers);
    }

    public function testBuildHeadersDoesNotDuplicateAuthorizationWhenProvided(): void
    {
        $client = $this->makeClient();

        $headers = $client->buildHeadersPublic([
            'method' => 'GET',
            'url' => 'https://api.paymongo.com/v2/customers',
            'headers' => ['Authorization: Bearer jwt_customer_token'],
        ]);

        $authorizationHeaders = array_values(array_filter(
            $headers,
            static fn (string $header): bool => str_starts_with($header, 'Authorization:')
        ));

        $this->assertSame(['Authorization: Bearer jwt_customer_token'], $authorizationHeaders);
    }

    public function testBuildHeadersKeepsCustomContentType(): void
    {
        $client = $this->makeClient();

        $headers = $client->buildHeadersPublic([
            'method' => 'POST',
            'url' => 'https://api.paymongo.com/v1/file_records',
            'headers' => ['Content-Type: multipart/form-data; boundary=abc123'],
        ]);

        $contentTypeHeaders = array_values(array_filter(
            $headers,
            static fn (string $header): bool => str_starts_with($header, 'Content-Type:')
        ));

        $this->assertSame(['Content-Type: multipart/form-data; boundary=abc123'], $contentTypeHeaders);
    }

    public function testBuildRequestBodyUsesRawBodyWhenPresent(): void
    {
        $client = $this->makeClient();

        $body = $client->buildBodyPublic([
            'method' => 'POST',
            'url' => 'https://api.paymongo.com/v1/file_records',
            'body' => 'raw multipart body',
            'params' => ['ignored' => true],
        ]);

        $this->assertSame('raw multipart body', $body);
    }

    public function testBuildRequestBodyWrapsJsonApiAttributes(): void
    {
        $client = $this->makeClient();

        $body = $client->buildBodyPublic([
            'method' => 'POST',
            'url' => 'https://api.paymongo.com/v1/payments',
            'params' => [
                'amount' => 1000,
                'currency' => 'PHP',
            ],
        ]);

        $this->assertSame(
            json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => 1000,
                        'currency' => 'PHP',
                    ],
                ],
            ]),
            $body
        );
    }

    public function testPatchRequestsBuildJsonBody(): void
    {
        $client = $this->makeClient();

        $body = $client->buildBodyPublic([
            'method' => 'PATCH',
            'url' => 'https://api.paymongo.com/v1/customers/cus_test_123',
            'params' => [
                'email' => 'updated@example.com',
            ],
        ]);

        $this->assertSame(
            json_encode([
                'data' => [
                    'attributes' => [
                        'email' => 'updated@example.com',
                    ],
                ],
            ]),
            $body
        );
    }

    public function testBuildErrorExceptionWrapsNonJson404Body(): void
    {
        $client = $this->makeClient();

        $exception = $client->buildErrorExceptionPublic('Not Found', 404, 'https://api.paymongo.com/v1/missing');

        $this->assertInstanceOf(ResourceNotFoundException::class, $exception);
        $this->assertSame('Not Found', $exception->getError()[0]->detail);
    }

    public function testBuildErrorExceptionUsesRouteNotFoundForEmpty404Body(): void
    {
        $client = $this->makeClient();

        $exception = $client->buildErrorExceptionPublic('', 404, 'https://api.paymongo.com/v1/missing');

        $this->assertInstanceOf(RouteNotFoundException::class, $exception);
        $this->assertSame('Route https://api.paymongo.com/v1/missing not found.', $exception->getMessage());
    }

    public function testBuildErrorExceptionWrapsNonJson5xxBody(): void
    {
        $client = $this->makeClient();

        $exception = $client->buildErrorExceptionPublic('Gateway failure', 502, 'https://api.paymongo.com/v1/payments');

        $this->assertInstanceOf(BaseException::class, $exception);
        $this->assertSame('Gateway failure', $exception->getError()[0]->detail);
    }
}
