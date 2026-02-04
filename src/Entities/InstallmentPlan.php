<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class InstallmentPlan extends BaseEntity
{
    public string $id;
    public string $type;
    public int $installments;
    public int $min_amount;
    public int $max_amount;
    public float $interest_rate;
    public string $issuer;
    public string $status;
    public bool $livemode;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->type = self::requireAttr($attributes, 'type');
        $this->installments = self::requireAttr($attributes, 'installments');
        $this->min_amount = self::requireAttr($attributes, 'min_amount');
        $this->max_amount = self::requireAttr($attributes, 'max_amount');
        $this->interest_rate = self::requireAttr($attributes, 'interest_rate');
        $this->issuer = self::requireAttr($attributes, 'issuer');
        $this->status = self::requireAttr($attributes, 'status');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
