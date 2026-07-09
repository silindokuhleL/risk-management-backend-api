<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreControlRequest extends FormRequest
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
            'risk_id' => ['required', 'integer', 'exists:risks,id'],
            'owner_id' => ['nullable', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:Preventive,Detective,Corrective'],
            'effectiveness' => ['required', 'string', 'in:effective,partially_effective,ineffective,untested'],
            'status' => ['required', 'string', 'in:active,draft,retired,remediation_required'],
            'due_at' => ['nullable', 'date'],
            'tested_at' => ['nullable', 'date'],
        ];
    }
}
