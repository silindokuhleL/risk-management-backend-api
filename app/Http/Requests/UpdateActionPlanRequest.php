<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActionPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('view action plans') ?? false;
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
            'control_id' => ['sometimes', 'nullable', 'integer', 'exists:controls,id'],
            'owner_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'priority' => ['sometimes', 'required', 'string', 'in:low,medium,high,critical'],
            'status' => ['sometimes', 'required', 'string', 'in:open,in_progress,blocked,completed,cancelled'],
            'due_at' => ['sometimes', 'nullable', 'date'],
            'completed_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
