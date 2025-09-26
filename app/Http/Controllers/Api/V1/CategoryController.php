<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\UpsertCategoryDto;
use App\Http\Requests\Api\V1\UpsertCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use App\Actions\UpsertCategoryAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends ApiController
{
    public function __construct(
        protected UpsertCategoryAction $upsertCategoryAction,
    ) {
    }

    public function index(Request $request)
    {
        $categories = Category::latest()->get();

        return CategoryResource::collection($categories);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function store(UpsertCategoryRequest $request)
    {
        $dto = UpsertCategoryDto::fromArray($request->validated());

        $category = $this->upsertCategoryAction->handle($dto);

        // Clear categories cache
        $this->clearCategoriesCache();

        return new CategoryResource($category);
    }

    public function update(UpsertCategoryRequest $request, Category $category)
    {
        $dto = UpsertCategoryDto::fromArray($request->validated());

        $category = $this->upsertCategoryAction->handle($dto, $category->id);

        // Clear categories cache
        $this->clearCategoriesCache();

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        // Clear categories cache
        $this->clearCategoriesCache();

        return response()->noContent();
    }

    private function clearCategoriesCache()
    {
        $keys = Cache::get('categories_cache_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('categories_cache_keys');
    }
}
