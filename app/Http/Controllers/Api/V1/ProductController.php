<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\UpsertProductDto;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductsResource;
use App\Models\Product;
use App\Actions\UpsertProductAction;
use App\Http\Requests\Api\V1\UpsertProductRequest;
use Illuminate\Http\Request;

class ProductController extends ApiController
{

    public function __construct(
        protected UpsertProductAction $upsertProductAction,
    ) {}

    public function index(Request $request)
    {
        $query = Product::query();

        // Define all relationships that can be included
        $availableRelationships = ['category'];

        foreach ($availableRelationships as $relation) {
            if ($this->include($relation)) {
                $query->with($relation);
            }
        }

        $products = $query->get();

        return ProductsResource::collection($products);
    }


    public function show(Product $product)
    {
        return new ProductsResource($product);
    }

    public function store(UpsertProductRequest $request)
    {
        $dto = UpsertProductDto::fromArray($request->validated());

        $product = $this->upsertProductAction->handle($dto);

        return new ProductsResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $dto = UpsertProductDto::fromArray($request->validated());

        $product = $this->upsertProductAction->handle($dto, $product->sku);

        return new ProductsResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
