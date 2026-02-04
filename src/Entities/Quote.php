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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->converted_amount = self::requireAttr($attributes, 'converted_amount');
        $this->converted_currency = self::requireAttr($attributes, 'converted_currency');
        $this->provider = self::requireAttr($attributes, 'provider');
        $this->rate_id = self::requireAttr($attributes, 'rate_id');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
