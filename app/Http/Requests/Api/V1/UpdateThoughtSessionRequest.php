<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ThoughtDomain;
use App\Enums\ThoughtEmotion;
use App\Enums\ThoughtSessionStage;
use App\Enums\ThoughtSessionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateThoughtSessionRequest extends FormRequest
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
            'domain'             => ['nullable', 'string', new Enum(ThoughtDomain::class)],
            'negative_thought'   => ['nullable', 'string', 'min:5'],
            'intensity'          => ['nullable', 'integer', 'between:0,100'],
            'impact'             => ['nullable', 'string'],

            'trigger'            => ['nullable', 'string'],
            'emotions'           => ['nullable', 'array'],
            'emotions.*'         => ['distinct', new Enum(ThoughtEmotion::class)],
            'distortion'         => ['nullable', 'string'],
            'evidence_for'       => ['nullable', 'string'],
            'evidence_against'   => ['nullable', 'string'],

            'reframed_thought'   => ['nullable', 'string'],
            'ai_response'        => ['nullable', 'string'],
            'action_plan'        => ['nullable', 'string'],

            'felt_better'        => ['nullable', 'integer', 'between:0,100'],
            'reflection'         => ['nullable', 'string'],
            'reminder_at'        => ['nullable', 'date'],

            'current_stage'      => ['nullable', new Enum(ThoughtSessionStage::class)],
            'status'             => ['nullable', new Enum(ThoughtSessionStatus::class)]
        ];
    }
}
