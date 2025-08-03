<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ThoughtDomain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreThoughtSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domain'           => ['required', 'string', new Enum(ThoughtDomain::class)],
            'negative_thought' => ['required', 'string', 'min:10'],
            'intensity'        => ['required', 'integer', 'between:0,100'],
            'impact'           => ['nullable', 'string'],
        ];
    }
}
