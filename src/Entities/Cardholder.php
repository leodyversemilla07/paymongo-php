<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Cardholder extends BaseEntity
{
    public string $id;
    public ?Billing $billing;
    public string $email;
    public string $first_name;
    public string $last_name;
    public bool $livemode;
    public ?string $phone;
    public string $status;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->billing = empty($attributes['billing']) ? null : new Billing(new ApiResource($attributes['billing']));
        $this->email = $attributes['email'];
        $this->first_name = $attributes['first_name'];
        $this->last_name = $attributes['last_name'];
        $this->livemode = $attributes['livemode'];
        $this->phone = $attributes['phone'];
        $this->status = $attributes['status'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
