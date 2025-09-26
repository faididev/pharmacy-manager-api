<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpsertProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('product') !== null;

        return [
            'name' => $isUpdate ? 'sometimes|string|max:255' : 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => $isUpdate ? 'sometimes|numeric|min:0' : 'required|numeric|min:0',
            'quantity' => $isUpdate ? 'sometimes|integer|min:0' : 'required|integer|min:0',
            'total' => 'nullable|numeric|min:0',
            'manufacture_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufacture_date',
            'category_id' => $isUpdate ? 'sometimes|exists:categories,id' : 'required|exists:categories,id',
        ];
    }
}
