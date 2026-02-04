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
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->archived = self::requireAttr($attributes, 'archived');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->description = self::attr($attributes, 'description');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->fee = self::requireAttr($attributes, 'fee');
        $this->remarks = self::attr($attributes, 'remarks');
        $this->status = self::requireAttr($attributes, 'status');
        $this->tax_amount = self::attr($attributes, 'tax_amount');
        $this->checkout_url = self::requireAttr($attributes, 'checkout_url');
        $this->reference_number = self::attr($attributes, 'reference_number');
        $this->payments = null;
        
        if (!empty(self::attr($attributes, 'payments'))) {
            $this->payments = [];

            foreach ($attributes['payments'] as $payment) {
                $this->payments[] = new Payment($payment);
            }
        }
        
        $this->taxes = self::attr($attributes, 'taxes');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}