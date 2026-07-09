<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'status' => $this->status,
            'owner' => $this->whenLoaded('owner', fn () => [
                'id' => $this->owner?->id,
                'name' => $this->owner?->name,
                'email' => $this->owner?->email,
            ]),
            'inherent_likelihood' => $this->inherent_likelihood,
            'inherent_impact' => $this->inherent_impact,
            'inherent_score' => $this->inherentScore(),
            'residual_likelihood' => $this->residual_likelihood,
            'residual_impact' => $this->residual_impact,
            'residual_score' => $this->residualScore(),
            'identified_at' => $this->identified_at?->toDateString(),
            'reviewed_at' => $this->reviewed_at?->toDateString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
