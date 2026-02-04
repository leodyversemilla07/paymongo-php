<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Rate extends BaseEntity
{
    public string $id;
    public string $currency_pair;
    public string $provider;
    public float $rate;
    public bool $livemode;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->currency_pair = self::requireAttr($attributes, 'currency_pair');
        $this->provider = self::requireAttr($attributes, 'provider');
        $this->rate = self::requireAttr($attributes, 'rate');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
