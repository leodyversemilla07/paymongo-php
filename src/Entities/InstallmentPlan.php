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
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->type = $attributes['type'];
        $this->installments = $attributes['installments'];
        $this->min_amount = $attributes['min_amount'];
        $this->max_amount = $attributes['max_amount'];
        $this->interest_rate = $attributes['interest_rate'];
        $this->issuer = $attributes['issuer'];
        $this->status = $attributes['status'];
        $this->livemode = $attributes['livemode'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
