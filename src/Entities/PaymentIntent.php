<?php

declare(strict_types=1);

namespace Paymongo\Entities;

use Paymongo\ApiResource;

/**
 * Represents a PayMongo PaymentIntent.
 */
class PaymentIntent extends BaseEntity
{
    public string $id;
    public int $amount;
    public string $capture_type;
    public string $client_key;
    public string $currency;
    public ?string $description;
    public bool $livemode;
    public mixed $last_payment_error;
    public ?string $statement_descriptor;
    public string $status;
    /** @var array<string> */
    public array $payment_method_allowed;
    /** @var array<Payment>|null */
    public ?array $payments;
    public mixed $next_action;
    public mixed $payment_method_options;
    /** @var array<string, mixed>|null */
    public ?array $metadata;
    public mixed $setup_future_usage;
    public int $created_at;
    public int $updated_at;

    public function __construct(ApiResource $apiResource)
    {
        $attributes = $apiResource->attributes ?? [];

        $this->id = $apiResource->id;
        $this->amount = self::requireAttr($attributes, 'amount');
        $this->capture_type = self::requireAttr($attributes, 'capture_type');
        $this->client_key = self::requireAttr($attributes, 'client_key');
        $this->currency = self::requireAttr($attributes, 'currency');
        $this->description = self::attr($attributes, 'description');
        $this->livemode = self::requireAttr($attributes, 'livemode');
        $this->last_payment_error = self::attr($attributes, 'last_payment_error');
        $this->statement_descriptor = self::attr($attributes, 'statement_descriptor');
        $this->status = self::requireAttr($attributes, 'status');
        $this->payment_method_allowed = self::requireAttr($attributes, 'payment_method_allowed');
        $this->payments = null;

        if (!empty(self::attr($attributes, 'payments'))) {
            $this->payments = [];

            foreach ($attributes['payments'] as $payment) {
                $this->payments[] = new Payment(new ApiResource($payment));
            }
        }

        $this->next_action = self::attr($attributes, 'next_action');
        $this->payment_method_options = self::attr($attributes, 'payment_method_options');
        $this->metadata = empty(self::attr($attributes, 'metadata')) ? null : $attributes['metadata'];
        $this->setup_future_usage = self::attr($attributes, 'setup_future_usage');
        $this->created_at = self::requireAttr($attributes, 'created_at');
        $this->updated_at = self::requireAttr($attributes, 'updated_at');
    }
}