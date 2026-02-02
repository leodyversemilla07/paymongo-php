<?php

declare(strict_types=1);

namespace PayMongo;

use PayMongo\Exceptions\PublicKeyException;

/**
 * Static helper for setting the PayMongo API key.
 * 
 * @deprecated Use PaymongoClient instead for better encapsulation.
 */
class PayMongo
{
    public static ?string $apiKey = null;

    /**
     * Set the API key for PayMongo operations.
     *
     * @throws PublicKeyException If a public key is provided
     */
    public static function setApiKey(string $apiKey): void
    {
        if (str_starts_with($apiKey, 'pk_')) {
            throw new PublicKeyException('Public API keys are not supported.');
        }

        self::$apiKey = $apiKey;
    }
}
