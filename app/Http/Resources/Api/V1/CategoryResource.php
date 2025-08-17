<?php 

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type' => 'category',
            'uuid' => $this->uuid,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->description,
            ]
        ];
    }
}