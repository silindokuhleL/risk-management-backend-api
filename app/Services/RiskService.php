<?php

namespace App\Services;

use App\Models\Risk;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RiskService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return Risk::query()
            ->with('owner:id,name,email')
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['category'] ?? null, fn ($query, $category) => $query->where('category', $category))
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 15));
    }

    public function create(array $data): Risk
    {
        return Risk::query()->create($data)->load('owner:id,name,email');
    }

    public function update(Risk $risk, array $data): Risk
    {
        $risk->update($data);

        return $risk->refresh()->load('owner:id,name,email');
    }

    public function delete(Risk $risk): void
    {
        $risk->delete();
    }
}
