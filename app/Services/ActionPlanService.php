<?php

namespace App\Services;

use App\Models\ActionPlan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActionPlanService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return ActionPlan::query()
            ->with([
                'risk:id,title,category,status',
                'control:id,title,effectiveness,status',
                'owner:id,name,email',
            ])
            ->when($filters['risk_id'] ?? null, fn ($query, $riskId) => $query->where('risk_id', $riskId))
            ->when($filters['control_id'] ?? null, fn ($query, $controlId) => $query->where('control_id', $controlId))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['priority'] ?? null, fn ($query, $priority) => $query->where('priority', $priority))
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 15));
    }

    public function create(array $data): ActionPlan
    {
        return ActionPlan::query()
            ->create($data)
            ->load([
                'risk:id,title,category,status',
                'control:id,title,effectiveness,status',
                'owner:id,name,email',
            ]);
    }

    public function update(ActionPlan $actionPlan, array $data): ActionPlan
    {
        $actionPlan->update($data);

        return $actionPlan->refresh()->load([
            'risk:id,title,category,status',
            'control:id,title,effectiveness,status',
            'owner:id,name,email',
        ]);
    }

    public function delete(ActionPlan $actionPlan): void
    {
        $actionPlan->delete();
    }
}
