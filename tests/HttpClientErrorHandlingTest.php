<?php

declare(strict_types=1);

use Paymongo\HttpClient;
use Paymongo\Exceptions\BaseException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class HttpClientErrorHandlingTest extends TestCase
{
    private function makeTestClient(): HttpClient
    {
        return new class('') extends HttpClient {
            /**
             * @return array<string, mixed>
             */
            public function decodePublic(string $body): array
            {
                return $this->decodeJson($body);
            }

            public function curlFailPublic(string $error, int $errno): void
            {
                $this->throwCurlFailure($error, $errno);
            }
        };
    }

    public function testDecodeJsonThrowsOnInvalidJson(): void
    {
        $client = $this->makeTestClient();

        $this->expectException(BaseException::class);
        $client->decodePublic('{invalid_json');
    }

    public function testDecodeJsonReturnsEmptyArrayOnEmptyBody(): void
    {
        $client = $this->makeTestClient();

        $result = $client->decodePublic('');
        $this->assertSame([], $result);
    }

    public function testCurlFailureIncludesDetail(): void
    {
        $client = $this->makeTestClient();

        try {
            $client->curlFailPublic('timeout', 28);
            $this->fail('Expected BaseException to be thrown.');
        } catch (BaseException $e) {
            $errors = $e->getError();
            $this->assertNotEmpty($errors);
            $this->assertSame('cURL error 28: timeout', $errors[0]->detail);
        }
    }
}
