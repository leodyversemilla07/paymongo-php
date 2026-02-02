<?php

declare(strict_types=1);

namespace Paymongo\Entities;

/**
 * Represents billing information.
 */
class Billing
{
    public BillingAddress $address;
    public ?string $email;
    public ?string $name;
    public ?string $phone;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->address = new BillingAddress($data['address']);
        $this->email = $data['email'];
        $this->name = $data['name'];
        $this->phone = $data['phone'];
    }
}