<?php 
namespace App\Http\Controllers\Api\V1;

use App\DTOs\UpsertProductDto;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductsResource;
use App\Models\Product;
use App\Actions\UpsertProductAction;


class ProductController
{

    public function __construct(
       protected UpsertProductAction $upsertProductAction,
    ){}

    public function index()
    {
        return ProductsResource::collection(Product::all());
    }

    public function show(Product $product)
    {
        return new ProductsResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        $dto = UpsertProductDto::fromArray($request->validated());
        $product = $this->upsertProductAction->handle($dto);
        return new ProductsResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {

        $dto = UpsertProductDto::fromArray($request->validated());

        $product = $this->upsertProductAction->handle( $dto);

        return new ProductsResource($product);
        
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}