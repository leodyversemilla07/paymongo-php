<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo CheckoutSession.
 */
class CheckoutSession extends BaseEntity
{
    public string $id;
    public ?Billing $billing;
    public ?string $cancel_url;
    public ?string $description;
    /** @var array<mixed> */
    public array $line_items;
    public bool $livemode;
    /** @var array<string> */
    public array $payment_method_types;
    public ?string $reference_number;
    public ?string $success_url;
    public string $status;
    public ?string $url;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->billing = empty(self::attr($attributes, 'billing')) ? null : new Billing($attributes['billing']);
        $this->cancel_url = self::attr($attributes, 'cancel_url');
        $this->description = self::attr($attributes, 'description');
        $this->line_items = self::requireAttr($attributes, 'line_items');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->payment_method_types = self::requireAttr($attributes, 'payment_method_types');
        $this->reference_number = self::attr($attributes, 'reference_number');
        $this->success_url = self::attr($attributes, 'success_url');
        $this->status = self::requireAttr($attributes, 'status');
        $this->url = self::attr($attributes, 'url');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}
