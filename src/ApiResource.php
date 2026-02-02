<?php

declare(strict_types=1);

namespace Paymongo;

/**
 * Wrapper for API responses from PayMongo.
 */
class ApiResource
{
    /** @var mixed Raw response data */
    public mixed $data;
    
    /** @var array<string, mixed>|null Response attributes */
    public ?array $attributes;
    
    /** @var string|null Resource ID */
    public ?string $id;
    
    /** @var bool|null Indicates if there are more results */
    public ?bool $hasMore;

    /**
     * @param array<string, mixed> $response The raw API response
     */
    public function __construct(array $response)
    {
        $this->data = array_key_exists('data', $response) ? $response['data'] : $response;

        if (is_array($this->data) && array_key_exists('attributes', $this->data)) {
            $this->attributes = $this->data['attributes'];
            $this->id = $this->data['id'];
        } else {
            $this->attributes = null;
            $this->id = null;
        }

        $this->hasMore = array_key_exists('has_more', $response) ? $response['has_more'] : null;
    }
}