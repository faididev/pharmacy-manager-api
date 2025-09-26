<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\UpsertProductDto;
use App\Http\Resources\Api\V1\ProductsResource;
use App\Models\Product;
use App\Actions\UpsertProductAction;
use App\Http\Requests\Api\V1\UpsertProductRequest;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v1/products",
 *     operationId="getProducts",
 *     tags={"Products"},
 *     summary="Get all products",
 *     description="Retrieve all products with pagination, search, and filtering options",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of items per page",
 *         required=false,
 *         @OA\Schema(type="integer", default=15)
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search term for product name, description, or SKU",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="category_id",
 *         in="query",
 *         description="Filter by category ID",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="include",
 *         in="query",
 *         description="Include relationships (e.g., category)",
 *         required=false,
 *         @OA\Schema(type="string", example="category")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Products retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product")),
 *             @OA\Property(property="links", type="object"),
 *             @OA\Property(property="meta", type="object")
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Product",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Paracetamol 500mg"),
 *     @OA\Property(property="description", type="string", example="Pain relief medication"),
 *     @OA\Property(property="price", type="number", format="float", example=5.99),
 *     @OA\Property(property="quantity", type="integer", example=100),
 *     @OA\Property(property="sku", type="string", example="PROD-001"),
 *     @OA\Property(property="manufacture_date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="expiry_date", type="string", format="date", example="2026-01-15"),
 *     @OA\Property(property="category_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="category", ref="#/components/schemas/Category")
 * )
 */
class ProductController extends ApiController
{

    public function __construct(
        protected UpsertProductAction $upsertProductAction,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/products",
     *     operationId="createProduct",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     description="Create a new product",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "price", "quantity", "category_id"},
     *                 @OA\Property(property="name", type="string", example="Paracetamol 500mg"),
     *                 @OA\Property(property="description", type="string", example="Pain relief medication"),
     *                 @OA\Property(property="price", type="number", format="float", example=5.99),
     *                 @OA\Property(property="quantity", type="integer", example=100),
     *                 @OA\Property(property="manufacture_date", type="string", format="date", example="2024-01-15"),
     *                 @OA\Property(property="expiry_date", type="string", format="date", example="2026-01-15"),
     *                 @OA\Property(property="category_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */

    /**
     * @OA\Put(
     *     path="/api/v1/products/{product}",
     *     operationId="updateProduct",
     *     tags={"Products"},
     *     summary="Update a product",
     *     description="Update an existing product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Paracetamol 500mg"),
     *                 @OA\Property(property="description", type="string", example="Pain relief medication"),
     *                 @OA\Property(property="price", type="number", format="float", example=5.99),
     *                 @OA\Property(property="quantity", type="integer", example=100),
     *                 @OA\Property(property="manufacture_date", type="string", format="date", example="2024-01-15"),
     *                 @OA\Property(property="expiry_date", type="string", format="date", example="2026-01-15"),
     *                 @OA\Property(property="category_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $categoryId = $request->get('category_id');

        $query = Product::query()->latest();

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Define all relationships that can be included
        $availableRelationships = ['category'];

        foreach ($availableRelationships as $relation) {
            if ($this->include($relation)) {
                $query->with($relation);
            }
        }

        $products = $query->paginate($perPage);

        return ProductsResource::collection($products);
    }

    public function show(Product $product)
    {
        return new ProductsResource($product);
    }

    public function store(UpsertProductRequest $request)
    {
        $data = $request->validated();

        $dto = UpsertProductDto::fromArray($data);

        $product = $this->upsertProductAction->handle($dto);

        return new ProductsResource($product);
    }

    public function update(UpsertProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $dto = UpsertProductDto::fromArray($data);

        $product = $this->upsertProductAction->handle($dto, $product->sku);

        return new ProductsResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }

}
