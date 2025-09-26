<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpsertOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('order') !== null;

        return [
            'customer_id' => $isUpdate ? 'sometimes|exists:customers,id' : 'required|exists:customers,id',
            'order_date'  => 'nullable|date',
            'status'      => 'sometimes|string|in:pending,processing,completed,canceled',

            // nested items
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
        ];
    }
}
