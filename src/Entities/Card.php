<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Card extends BaseEntity
{
    public string $id;
    public string $brand;
    public string $currency;
    public string $last4;
    public bool $livemode;
    public string $status;
    public mixed $cardholder;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->brand = $attributes['brand'];
        $this->currency = $attributes['currency'];
        $this->last4 = $attributes['last4'];
        $this->livemode = $attributes['livemode'];
        $this->status = $attributes['status'];
        $this->cardholder = $attributes['cardholder'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
