<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'type' => 'customer',
            'id'   => $this->id,
            'attributes' => [
                'loyalty_points' => $this->loyalty_points,
                'createdAt'      => $this->created_at,
                'updatedAt'      => $this->updated_at,
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id'   => $this->user_id,
                    ],
                    'links' => [
                        'self' => route('customers.show', $this->user_id),
                    ],
                ],
            ],
            'includes' => [
                'user' => new UserResource($this->whenLoaded('user')),
            ],
        ];
    }
}
