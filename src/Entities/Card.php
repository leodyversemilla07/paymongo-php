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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->brand = self::requireAttr($attributes, 'brand');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->last4 = self::requireAttr($attributes, 'last4');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->status = self::requireAttr($attributes, 'status');
        $this->cardholder = self::attr($attributes, 'cardholder');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
