<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRiskRequest extends FormRequest
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
            'owner_id' => ['nullable', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:80'],
            'inherent_likelihood' => ['required', 'integer', 'between:1,5'],
            'inherent_impact' => ['required', 'integer', 'between:1,5'],
            'residual_likelihood' => ['nullable', 'integer', 'between:1,5'],
            'residual_impact' => ['nullable', 'integer', 'between:1,5'],
            'status' => ['required', 'string', 'in:open,monitoring,mitigating,closed'],
            'identified_at' => ['nullable', 'date'],
            'reviewed_at' => ['nullable', 'date'],
        ];
    }
}
