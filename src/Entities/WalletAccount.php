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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->account_name = self::requireAttr($attributes, 'account_name');
        $this->account_number = self::requireAttr($attributes, 'account_number');
        $this->account_type = self::requireAttr($attributes, 'account_type');
        $this->bank_code = self::requireAttr($attributes, 'bank_code');
        $this->bank_name = self::requireAttr($attributes, 'bank_name');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->status = self::requireAttr($attributes, 'status');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
