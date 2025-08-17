<?php 

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class OrderResource extends JsonResource
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
                'customer_id' => $this->customer_id,
                'order_id' => $this->order_id,
                'total_amount' => $this->total_amount,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
        ];
    }
}