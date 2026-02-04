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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->type = self::requireAttr($attributes, 'type');
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->description = self::attr($attributes, 'description');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->status = self::requireAttr($attributes, 'status');
        $this->redirect = self::requireAttr($attributes, 'redirect');
        $this->billing = ( (self::attr($attributes, 'billing')) === null ) ? null : new Billing($attributes['billing']);
        $this->metadata = self::attr($attributes, 'metadata');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}