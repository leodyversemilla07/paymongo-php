<?php

declare(strict_types=1);

namespace Paymongo\Exceptions;

use Exception;
use Paymongo\Error;

/**
 * Exception for API-related errors.
 * 
 * @deprecated Use BaseException or specific exception types instead.
 */
class ApiException extends Exception
{
    public ?string $jsonBody;

    public function __construct(?string $jsonBody = null)
    {
        parent::__construct();
        $this->jsonBody = $jsonBody;
    }

    /**
     * Get errors from the API response.
     *
     * @param string $attribute Filter by attribute name
     * @return array<Error>
     */
    public function errors(string $attribute = ''): array
    {
        if (!empty($this->jsonBody)) {
            if (!empty($attribute)) {
                $errors = $this->errors();

                return array_filter($errors, function (Error $error) use ($attribute): bool {
                    return $error->hasSource() && $error->source?->attribute === $attribute;
                });
            }

            $body = json_decode($this->jsonBody, true);
            if (isset($body['errors'])) {
                return array_map(fn(array $error): Error => new Error($error), $body['errors']);
            }
        }

        return [];
    }

    /**
     * Create an exception instance from an error message and JSON body.
     */
    public static function factory(string $message, string $jsonBody): static
    {
        $message .= ' ' . self::digestApiError($jsonBody);
        $instance = new static($jsonBody);
        $instance->message = $message;

        return $instance;
    }

    /**
     * Extract error details from JSON body.
     */
    public static function digestApiError(string $jsonBody): string
    {
        $body = json_decode($jsonBody, true);
        $apiErrorMessage = '';

        if (isset($body['errors']) && is_array($body['errors'])) {
            foreach ($body['errors'] as $error) {
                $type = $error['meta']['type'] ?? 'unknown';
                $code = $error['code'] ?? 'unknown';
                $detail = $error['detail'] ?? '';
                $apiErrorMessage .= "{$type}: {$code} - {$detail}";
            }
        }

        return $apiErrorMessage;
    }
}
