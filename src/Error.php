<?php

declare(strict_types=1);

namespace Paymongo;

/**
 * Represents an error from the PayMongo API.
 */
class Error
{
    public ?string $code;
    public ?string $detail;
    public ?SourceError $source;
    
    /** @var array<string, mixed> */
    private array $error;

    /**
     * @param array<string, mixed> $error The error data from the API
     */
    public function __construct(array $error)
    {
        $this->error = $error;
        $this->code = array_key_exists('code', $error) ? $error['code'] : null;
        $this->detail = array_key_exists('detail', $error) ? $error['detail'] : null;
        $this->source = null;

        if ($this->hasSource()) {
            $this->source = new SourceError($error['source']);
        }
    }

    /**
     * Check if the error has source information.
     */
    public function hasSource(): bool
    {
        return array_key_exists('source', $this->error);
    }
}
