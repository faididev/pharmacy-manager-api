<?php 
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Requests\Api\V1\UpsertCategoryRequest;
use App\DTOs\UpsertCategoryDto;
use App\Models\Category;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Actions\UpsertCategoryAction;

class CategoryController
{

    public function __construct(
       protected UpsertCategoryAction $upsertCategoryAction,
    ){}

    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function store(UpsertCategoryRequest $request)
    {
        $dto = UpsertCategoryDto::fromArray($request->validated());
        $category = $this->upsertCategoryAction->handle($dto);
        return new CategoryResource($category);
    }

    public function update(UpdateProductRequest $request, Category $category)
    {

        $dto = UpsertCategoryDto::fromArray($request->validated());

        $category = $this->upsertCategoryAction->handle( $dto);

        return new CategoryResource($category);
        
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}