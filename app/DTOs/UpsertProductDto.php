<?php

namespace App\DTOs;

use Carbon\Carbon;

class UpsertProductDto
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly int $quantity,
        public readonly int $categoryId,
        public readonly ?string $description = null,
        public readonly ?float $total = null,
        public readonly ?Carbon $manufactureDate = null,
        public readonly ?Carbon $expiryDate = null,
    ) {}

    public static function fromArray(array $data)
    {
        return new self(
            name: $data['name'],
            price: $data['price'],
            quantity: $data['quantity'],
            categoryId: $data['category_id'],
            description: $data['description'] ?? null,
            total: $data['total'] ?? null,
            manufactureDate: isset($data['manufacture_date']) ? Carbon::parse($data['manufacture_date']) : null,
            expiryDate: isset($data['expiry_date']) ? Carbon::parse($data['expiry_date']) : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'manufacture_date' => $this->manufactureDate?->toDateString(),
            'expiry_date' => $this->expiryDate?->toDateString(),
            'category_id' => $this->categoryId,
        ], fn($value) => !is_null($value));
    }
}
