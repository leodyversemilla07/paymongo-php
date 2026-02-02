<?php

declare(strict_types=1);

namespace Paymongo\Exceptions;

use Exception;

/**
 * Exception thrown when webhook signature verification fails.
 */
class SignatureVerificationException extends Exception
{
}