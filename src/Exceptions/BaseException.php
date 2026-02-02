<?php

declare(strict_types=1);

namespace Paymongo\Exceptions;

use Exception;
use Paymongo\Error;

/**
 * Base exception for PayMongo API errors.
 */
class BaseException extends Exception
{
    /** @var array<string, mixed>|null */
    private ?array $data;
    
    /** @var array<array<string, mixed>> */
    protected array $errors;

    /**
     * @param array<string, mixed>|null $data The error data from the API
     */
    public function __construct(?array $data = null)
    {
        parent::__construct();
        
        $this->data = $data;
        $this->errors = [];

        if (is_array($this->data) && array_key_exists('errors', $this->data)) {
            $this->errors = $this->data['errors'];
        }
    }

    /**
     * Get the error objects from this exception.
     *
     * @return array<Error>
     */
    public function getError(): array
    {
        $errors = [];

        foreach ($this->errors as $error) {
            $errors[] = new Error($error);
        }

        return $errors;
    }

    /**
     * Get the raw error data.
     *
     * @return array<string, mixed>|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }
}
