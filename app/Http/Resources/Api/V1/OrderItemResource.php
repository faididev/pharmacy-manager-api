<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'type' => 'order_item',
            'id'   => $this->id,
            'attributes' => [
                'product_id' => $this->product_id,
                'quantity'   => $this->quantity,
                'price'      => $this->price,
            ],
            'relationships' => [
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id'   => $this->product_id,
                    ],
                    'links' => [
                        'self' => route('products.show', $this->product_id),
                    ]
                ]
            ]
        ];
    }
}
