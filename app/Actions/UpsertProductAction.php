<?php

namespace App\Actions;

use App\DTOs\UpsertProductDto;
use App\Models\Product;

class UpsertProductAction
{
    public function handle(UpsertProductDto $dto, ?string $sku = null): Product
    {
        return Product::updateOrCreate(
            ['sku' => $sku],
            [
                'name'             => $dto->name,
                'description'      => $dto->description,
                'price'            => $dto->price,
                'quantity'         => $dto->quantity,
                'total'            => $dto->total ?? $dto->price * $dto->quantity,
                'manufacture_date' => $dto->manufactureDate?->toDateString(),
                'expiry_date'      => $dto->expiryDate?->toDateString(),
                'category_id'      => $dto->categoryId,
            ]
        );
    }
}
