<?php

namespace App\DTOs;

class UpsertCustomerDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $phone = null,
        public readonly ?string $address = null,
        public readonly ?string $password = null,
        public readonly int $loyaltyPoints = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            loyaltyPoints: $data['loyalty_points'] ?? 0,
        );
    }
}
