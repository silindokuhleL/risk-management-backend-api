<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRiskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('view risks') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'owner_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'category' => ['sometimes', 'required', 'string', 'max:80'],
            'inherent_likelihood' => ['sometimes', 'required', 'integer', 'between:1,5'],
            'inherent_impact' => ['sometimes', 'required', 'integer', 'between:1,5'],
            'residual_likelihood' => ['sometimes', 'nullable', 'integer', 'between:1,5'],
            'residual_impact' => ['sometimes', 'nullable', 'integer', 'between:1,5'],
            'status' => ['sometimes', 'required', 'string', 'in:open,monitoring,mitigating,closed'],
            'identified_at' => ['sometimes', 'nullable', 'date'],
            'reviewed_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
