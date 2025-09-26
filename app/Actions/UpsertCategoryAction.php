<?php

namespace App\Actions;

use App\DTOs\UpsertCategoryDto;
use App\Models\Category;

class UpsertCategoryAction
{
    public function handle(UpsertCategoryDto $dto, ?int $id = null): Category
    {
        return Category::updateOrCreate(
            ['id' => $id],
            [
                'name' => $dto->name,
                'description' => $dto->description,
            ]
        );
    }
}
