<?php

declare(strict_types=1);

namespace Paymongo\Exceptions;

use Paymongo\Error;

/**
 * Exception thrown when a resource is not found.
 */
class ResourceNotFoundException extends BaseException
{
    /**
     * Get the first error from this exception.
     *
     * @return array<Error>
     */
    public function getError(): array
    {
        if (empty($this->errors)) {
            return [];
        }
        
        return [new Error($this->errors[0])];
    }
    
    /**
     * Get the first error object.
     */
    public function getFirstError(): ?Error
    {
        if (empty($this->errors)) {
            return null;
        }
        
        return new Error($this->errors[0]);
    }
}