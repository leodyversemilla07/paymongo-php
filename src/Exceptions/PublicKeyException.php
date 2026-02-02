<?php

declare(strict_types=1);

namespace PayMongo\Exceptions;

use Exception;

/**
 * Exception thrown when a public key is used instead of a secret key.
 */
class PublicKeyException extends Exception
{
}
