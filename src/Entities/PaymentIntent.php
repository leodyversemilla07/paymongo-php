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
        $attributes = $apiResource->attributes;

        $this->id = $apiResource->id;
        $this->amount = $attributes['amount'];
        $this->capture_type = $attributes['capture_type'];
        $this->client_key = $attributes['client_key'];
        $this->currency = $attributes['currency'];
        $this->description = $attributes['description'];
        $this->livemode = $attributes['livemode'];
        $this->last_payment_error = $attributes['last_payment_error'];
        $this->statement_descriptor = $attributes['statement_descriptor'];
        $this->status = $attributes['status'];
        $this->payment_method_allowed = $attributes['payment_method_allowed'];
        $this->payments = null;

        if (!empty($attributes['payments'])) {
            $this->payments = [];

            foreach ($attributes['payments'] as $payment) {
                $this->payments[] = new Payment($payment);
            }
        }

        $this->next_action = $attributes['next_action'];
        $this->payment_method_options = $attributes['payment_method_options'];
        $this->metadata = empty($attributes['metadata']) ? null : $attributes['metadata'];
        $this->setup_future_usage = $attributes['setup_future_usage'];
        $this->created_at = $attributes['created_at'];
        $this->updated_at = $attributes['updated_at'];
    }
}