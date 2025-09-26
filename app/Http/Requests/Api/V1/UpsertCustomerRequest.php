<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpsertCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'=> 'required|string',
            'email'=> 'required|email',
            'phone'=> 'required',
            'address'=> 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'loyalty_points' => 'nullable|integer|min:0',
        ];
    }
}
