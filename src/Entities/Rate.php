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
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->currency_pair = $attributes['currency_pair'];
        $this->provider = $attributes['provider'];
        $this->rate = $attributes['rate'];
        $this->livemode = $attributes['livemode'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
