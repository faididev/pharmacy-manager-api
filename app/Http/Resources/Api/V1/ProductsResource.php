<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type' => 'product',
            'uuid' => $this->uuid,
            'attributes' => [
                'name' => $this->name,
                'sku' => $this->sku,
                'description' => $this->description,
                'image' => $this->getImageUrl(),
                'quantity' => $this->quantity,
                'total' => $this->total,
                'manufacture_date' => $this->manufacture_date,
                'expiry_date' => $this->expiry_date,
                'category_id' => $this->category_id,
                'price' => $this->price,
                $this->mergeWhen(
                    $request->routeIs('products.*'),
                    [
                        'createdAt' => $this->created_at,
                        'updatedAt' => $this->updated_at,
                    ]
                )
            ],
            'relationships' => [
                'category' => [
                    'data' => [
                        'type' => 'category',
                        'id' => $this->category_id
                    ],
                    'links' => [
                        'self' => route('categories.show', ['category' => $this->category_id])
                    ]
                ]
            ],
            'includes' => [
                'category' => new CategoryResource($this->whenLoaded('category'))
            ]
        ];
    }

    /**
     * Get the image URL for the product
     */
    private function getImageUrl(): ?string
    {
        if (!$this->image) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Skip temporary file paths (like /tmp/php...)
        if (str_starts_with($this->image, '/tmp/')) {
            return null;
        }

        // If it's a storage path, generate the proper URL
        if (str_starts_with($this->image, 'products/')) {
            return asset('storage/' . $this->image);
        }

        // For any other case, try to generate URL
        return asset('storage/' . $this->image);
    }
}
