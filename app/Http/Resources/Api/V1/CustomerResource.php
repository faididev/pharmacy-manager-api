<?php 

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class CustomerResource extends JsonResource
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
            'type' => 'customer',
            'uuid' => $this->uuid,
            'attributes' => [
                'user_id' => $this->user_id,
                'loyalty_points' => $this->loyalty_points,
            ],
        ];
    }
}