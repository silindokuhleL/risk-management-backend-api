<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'risks' => $this->resource['risks'],
            'controls' => $this->resource['controls'],
            'action_plans' => $this->resource['action_plans'],
        ];
    }
}
