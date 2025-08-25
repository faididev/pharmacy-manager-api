<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Support\Str;

class UpsertProductDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly float $price,
        public readonly int $quantity,
        public readonly ?float $total = null,
        public readonly ?Carbon $manufactureDate = null,
        public readonly ?Carbon $expiryDate = null,
        public readonly int $categoryId,
    ) {}

    public static function fromArray(array $data)
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            price: $data['price'],
            quantity: $data['quantity'],
            total: $data['total'] ?? null,
            manufactureDate: isset($data['manufacture_date']) ? Carbon::parse($data['manufacture_date']) : null,
            expiryDate: isset($data['expiry_date']) ? Carbon::parse($data['expiry_date']) : null,
            categoryId: $data['category_id'],
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
