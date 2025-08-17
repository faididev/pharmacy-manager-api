<?php 

namespace App\Actions;

use App\DTOs\UpsertCategoryDto;
use App\Models\Product;

class UpsertCategoryAction
{
    public function handle(UpsertCategoryDto $dto): Product
    {

        Product::upsert([
                'name'             => $dto->name,
                'description'      => $dto->description,
            ], ['name'], ['description'
        ]);

        return Product::where('name', trim($dto->name))->firstOrFail();   

    }
}