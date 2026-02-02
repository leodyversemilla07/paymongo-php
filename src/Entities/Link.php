<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo Link.
 */
class Link extends BaseEntity
{
    public string $id;
    public int $amount;
    public bool $archived;
    public string $currency;
    public ?string $description;
    public bool $livemode;
    public int $fee;
    public ?string $remarks;
    public string $status;
    public ?int $tax_amount;
    public string $checkout_url;
    public ?string $reference_number;
    /** @var array<Payment>|null */
    public ?array $payments;
    public mixed $taxes;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->amount = $attributes['amount'];
        $this->archived = $attributes['archived'];
        $this->currency = $attributes['currency'];
        $this->description = $attributes['description'];
        $this->livemode = $attributes['livemode'];
        $this->fee = $attributes['fee'];
        $this->remarks = $attributes['remarks'];
        $this->status = $attributes['status'];
        $this->tax_amount = $attributes['tax_amount'];
        $this->checkout_url = $attributes['checkout_url'];
        $this->reference_number = $attributes['reference_number'];
        $this->payments = null;
        
        if (!empty($attributes['payments'])) {
            $this->payments = [];

            foreach ($attributes['payments'] as $payment) {
                $this->payments[] = new Payment($payment);
            }
        }
        
        $this->taxes = $attributes['taxes'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}