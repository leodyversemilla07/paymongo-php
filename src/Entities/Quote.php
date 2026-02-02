<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Quote extends BaseEntity
{
    public string $id;
    public int $amount;
    public string $currency;
    public int $converted_amount;
    public string $converted_currency;
    public string $provider;
    public string $rate_id;
    public bool $livemode;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->amount = $attributes['amount'];
        $this->currency = $attributes['currency'];
        $this->converted_amount = $attributes['converted_amount'];
        $this->converted_currency = $attributes['converted_currency'];
        $this->provider = $attributes['provider'];
        $this->rate_id = $attributes['rate_id'];
        $this->livemode = $attributes['livemode'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
