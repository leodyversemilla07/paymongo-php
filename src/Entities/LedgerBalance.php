<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class LedgerBalance extends BaseEntity
{
    public string $id;
    public int $available_balance;
    public int $balance;
    public string $currency;
    public bool $livemode;
    public string $ledger_id;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->available_balance = self::requireAttr($attributes, 'available_balance');
        $this->balance = self::requireAttr($attributes, 'balance');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->ledger_id = self::requireAttr($attributes, 'ledger_id');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
