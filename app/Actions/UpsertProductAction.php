<?php 

namespace App\Actions;
use App\DTOs\UpsertProductDto;
use App\Models\Product;

class UpsertProductAction
{
    public function handle(UpsertProductDto $dto): Product
    {

        Product::upsert([
                'sku'              => $dto->sku,
                'name'             => $dto->name,
                'description'      => $dto->description,
                'price'            => $dto->price,
                'quantity'         => $dto->quantity,
                'total'            => $dto->total ?? $dto->price * $dto->quantity,
                'manufacture_date' => $dto->manufactureDate?->toDateString(),
                'expiry_date'      => $dto->expiryDate?->toDateString(),
                'category_id'      => $dto->categoryId,
            ], ['sku'], ['name', 'description', 'price', 'quantity', 'total', 'manufacture_date', 'expiry_date', 'category_id'
        ]);

        return Product::where('sku', trim($dto->sku))->firstOrFail();   

    }
}