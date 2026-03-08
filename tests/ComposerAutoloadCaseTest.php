<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ComposerAutoloadCaseTest extends TestCase
{
    public function testLegacyNamespaceFacadeAutoloadsViaComposer(): void
    {
        $this->assertTrue(class_exists(\PayMongo\PayMongo::class));
        $this->assertTrue(class_exists(\PayMongo\Exceptions\PublicKeyException::class));
    }
}
