<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class WalletAccount extends BaseEntity
{
    public string $id;
    public string $account_name;
    public string $account_number;
    public string $account_type;
    public string $bank_code;
    public string $bank_name;
    public string $currency;
    public bool $livemode;
    public string $status;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->account_name = $attributes['account_name'];
        $this->account_number = $attributes['account_number'];
        $this->account_type = $attributes['account_type'];
        $this->bank_code = $attributes['bank_code'];
        $this->bank_name = $attributes['bank_name'];
        $this->currency = $attributes['currency'];
        $this->livemode = $attributes['livemode'];
        $this->status = $attributes['status'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
