<?php

declare(strict_types=1);

namespace Paymongo;

/**
 * Represents the source of an API error.
 */
class SourceError
{
    public ?string $pointer;
    public ?string $attribute;

    /**
     * @param array<string, mixed> $source The source data from the error
     */
    public function __construct(array $source)
    {
        $this->pointer = array_key_exists('pointer', $source) ? $source['pointer'] : null;
        $this->attribute = array_key_exists('attribute', $source) ? $source['attribute'] : null;
    }
}
