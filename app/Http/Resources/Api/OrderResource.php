<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'type' => 'order',
            'id'   => $this->id,
            'attributes' => [
                'customer_id'  => $this->customer_id,
                'order_date'   => $this->order_date,
                'status'       => $this->status,
                'total_amount' => $this->total_amount,
                'createdAt'    => $this->created_at,
                'updatedAt'    => $this->updated_at,
            ],
            'relationships' => [
                'customer' => [
                    'data' => [
                        'type' => 'customer',
                        'id'   => $this->customer_id,
                    ],
                    'links' => [
                        'self' => route('customers.show', $this->customer_id),
                    ]
                ],
                'items' => OrderItemResource::collection($this->whenLoaded('items')),
            ],
        ];
    }
}
