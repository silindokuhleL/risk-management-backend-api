<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'risk' => $this->whenLoaded('risk', fn () => [
                'id' => $this->risk?->id,
                'title' => $this->risk?->title,
                'category' => $this->risk?->category,
                'status' => $this->risk?->status,
            ]),
            'control' => $this->whenLoaded('control', fn () => [
                'id' => $this->control?->id,
                'title' => $this->control?->title,
                'effectiveness' => $this->control?->effectiveness,
                'status' => $this->control?->status,
            ]),
            'owner' => $this->whenLoaded('owner', fn () => [
                'id' => $this->owner?->id,
                'name' => $this->owner?->name,
                'email' => $this->owner?->email,
            ]),
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'due_at' => $this->due_at?->toDateString(),
            'completed_at' => $this->completed_at?->toDateString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
