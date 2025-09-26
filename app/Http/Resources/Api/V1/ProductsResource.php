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

}
