<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

class Consumer extends BaseEntity
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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->billing = empty(self::attr($attributes, 'billing')) ? null : new Billing($attributes['billing']);
        $this->email = self::requireAttr($attributes, 'email');
        $this->first_name = self::requireAttr($attributes, 'first_name');
        $this->last_name = self::requireAttr($attributes, 'last_name');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->phone = self::attr($attributes, 'phone');
        $this->status = self::requireAttr($attributes, 'status');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
