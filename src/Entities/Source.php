<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Source extends BaseEntity
{
    public string $id;
    public string $type;
    public int $amount;
    public string $currency;
    public ?string $description;
    public bool $livemode;
    public string $status;
    /** @var array<string, mixed> */
    public array $redirect;
    public ?Billing $billing;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->type = $attributes['type'];
        $this->amount = $attributes['amount'];
        $this->currency = $attributes['currency'];
        $this->description = $attributes['description'];
        $this->livemode = $attributes['livemode'];
        $this->status = $attributes['status'];
        $this->redirect = $attributes['redirect'];
        $this->billing = is_null($attributes['billing']) ? null : new Billing($attributes['billing']);
        $this->metadata = $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}