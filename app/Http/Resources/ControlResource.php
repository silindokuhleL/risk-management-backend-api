<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ControlResource extends JsonResource
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
            'owner' => $this->whenLoaded('owner', fn () => [
                'id' => $this->owner?->id,
                'name' => $this->owner?->name,
                'email' => $this->owner?->email,
            ]),
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'effectiveness' => $this->effectiveness,
            'status' => $this->status,
            'due_at' => $this->due_at?->toDateString(),
            'tested_at' => $this->tested_at?->toDateString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
