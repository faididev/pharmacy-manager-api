<?php

namespace App\Actions;

use App\DTOs\UpsertProductDto;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpsertProductAction
{
    public function handle(UpsertProductDto $dto, ?string $sku = null): Product
    {
        $imagePath = null;
        
        // Handle image upload if provided
        if ($dto->image instanceof UploadedFile) {
            // Generate a unique filename
            $filename = time() . '_' . uniqid() . '.' . $dto->image->getClientOriginalExtension();
            $imagePath = $dto->image->storeAs('products', $filename, 'public');
        } elseif (is_string($dto->image) && !empty($dto->image)) {
            $imagePath = $dto->image;
        }

        return Product::updateOrCreate(
            ['sku' => $sku],
            [
                'name'             => $dto->name,
                'description'      => $dto->description,
                'image'            => $imagePath,
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
