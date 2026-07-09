<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateControlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('view controls') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'risk_id' => ['sometimes', 'required', 'integer', 'exists:risks,id'],
            'owner_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'type' => ['sometimes', 'required', 'string', 'in:Preventive,Detective,Corrective'],
            'effectiveness' => ['sometimes', 'required', 'string', 'in:effective,partially_effective,ineffective,untested'],
            'status' => ['sometimes', 'required', 'string', 'in:active,draft,retired,remediation_required'],
            'due_at' => ['sometimes', 'nullable', 'date'],
            'tested_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
