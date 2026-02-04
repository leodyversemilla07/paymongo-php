<?php

declare(strict_types=1);

use Paymongo\Tests\Support\HttpClientFake;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../initialize.php';

final class HttpClientDecodeTest extends TestCase
{
    public function testDecodeJsonReturnsArray(): void
    {
        $fake = new HttpClientFake();
        $body = json_encode(['data' => ['id' => 'pay_test_123']]);

        $decoded = $fake->decodePublic($body);

        $this->assertSame(['data' => ['id' => 'pay_test_123']], $decoded);
    }
}
