<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo webhook event.
 */
class Event extends BaseEntity
{
    public string $id;
    
    /** @var array<string, mixed> */
    public array $resource;
    
    public string $type;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->resource = $attributes['data'];
        $this->type = $attributes['type'];
    }
}