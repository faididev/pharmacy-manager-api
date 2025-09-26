<?php

namespace App\DTOs;

use Carbon\Carbon;

class UpsertOrderDto
{
    public function __construct(
        public readonly int $customerId,
        public readonly ?Carbon $orderDate = null,
        public readonly string $status = 'pending',
        /** @var array<array{product_id:int, quantity:int, price:float}> */
        public readonly array $items = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customerId: $data['customer_id'],
            orderDate: isset($data['order_date']) ? Carbon::parse($data['order_date']) : null,
            status: $data['status'] ?? 'pending',
            items: $data['items'] ?? [],
        );
    }
}
