<?php

declare(strict_types=1);

namespace Paymongo\Entities;

/**
 * Represents a billing address.
 */
class BillingAddress
{
    public ?string $city;
    public ?string $country;
    public ?string $line1;
    public ?string $line2;
    public ?string $postal_code;
    public ?string $state;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->city = $data['city'];
        $this->country = $data['country'];
        $this->line1 = $data['line1'];
        $this->line2 = $data['line2'];
        $this->postal_code = $data['postal_code'];
        $this->state = $data['state'];
    }
}
