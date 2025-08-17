<?php 

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'order',
            'uuid' => $this->uuid,
            'attributes' => [
                'order_id' => $this->order_id,
                'product_id' => $this->product_id,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
        ];
    }
}