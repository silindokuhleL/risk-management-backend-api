<?php

namespace App\Services;

use App\Models\Control;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ControlService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return Control::query()
            ->with(['risk:id,title,category,status', 'owner:id,name,email'])
            ->when($filters['risk_id'] ?? null, fn ($query, $riskId) => $query->where('risk_id', $riskId))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['effectiveness'] ?? null, fn ($query, $effectiveness) => $query->where('effectiveness', $effectiveness))
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 15));
    }

    public function create(array $data): Control
    {
        return Control::query()
            ->create($data)
            ->load(['risk:id,title,category,status', 'owner:id,name,email']);
    }

    public function update(Control $control, array $data): Control
    {
        $control->update($data);

        return $control->refresh()->load(['risk:id,title,category,status', 'owner:id,name,email']);
    }

    public function delete(Control $control): void
    {
        $control->delete();
    }
}
