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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->type = self::requireAttr($attributes, 'type');
        $this->billing = ( (self::attr($attributes, 'billing')) === null ) ? null : new Billing($attributes['billing']);
        $this->details = self::attr($attributes, 'details');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}