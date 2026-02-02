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
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->billing = empty($attributes['billing']) ? null : new Billing(new ApiResource($attributes['billing']));
        $this->cancel_url = $attributes['cancel_url'];
        $this->description = $attributes['description'];
        $this->line_items = $attributes['line_items'];
        $this->livemode = $attributes['livemode'];
        $this->payment_method_types = $attributes['payment_method_types'];
        $this->reference_number = $attributes['reference_number'];
        $this->success_url = $attributes['success_url'];
        $this->status = $attributes['status'];
        $this->url = $attributes['url'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}
