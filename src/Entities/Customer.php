<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo Customer.
 */
class Customer extends BaseEntity
{
    public string $id;
    public ?string $default_device;
    public ?string $default_payment_method_id;
    public ?string $email;
    public ?string $first_name;
    public ?string $last_name;
    public bool $livemode;
    public ?string $organization_id;
    public ?string $phone;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->default_device = self::attr($attributes, 'default_device');
        $this->default_payment_method_id = self::attr($attributes, 'default_payment_method_id');
        $this->email = self::attr($attributes, 'email');
        $this->first_name = self::attr($attributes, 'first_name');
        $this->last_name = self::attr($attributes, 'last_name');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->organization_id = self::attr($attributes, 'organization_id');
        $this->phone = self::attr($attributes, 'phone');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}