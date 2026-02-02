<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo PaymentMethod.
 */
class PaymentMethod extends BaseEntity
{
    public string $id;
    public string $type;
    public ?Billing $billing;
    public mixed $details;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->type = $attributes['type'];
        $this->billing = is_null($attributes['billing']) ? null : new Billing($attributes['billing']);
        $this->details = $attributes['details'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}