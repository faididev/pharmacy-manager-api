<?php 

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => 'sometimes|required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'sometimes|required|numeric|min:0',
            'quantity'         => 'sometimes|required|integer|min:0',
            'total'            => 'nullable|numeric|min:0',
            'manufacture_date' => 'nullable|date',
            'expiry_date'      => 'nullable|date|after_or_equal:manufacture_date',
            'category_id'      => 'sometimes|required|exists:categories,id',
        ];
    }
}