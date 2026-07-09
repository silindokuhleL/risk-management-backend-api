<?php

namespace App\Services;

use App\Models\ActionPlan;
use App\Models\Control;
use App\Models\Risk;

class DashboardSummaryService
{
    /**
     * Build the dashboard proof summary from the seeded risk workflow data.
     *
     * @return array<string, mixed>
     */
    public function summary(): array
    {
        return [
            'risks' => [
                'total' => Risk::query()->count(),
                'open' => Risk::query()->where('status', 'open')->count(),
                'mitigating' => Risk::query()->where('status', 'mitigating')->count(),
                'monitoring' => Risk::query()->where('status', 'monitoring')->count(),
                'high_inherent' => Risk::query()
                    ->whereRaw('inherent_likelihood * inherent_impact >= ?', [16])
                    ->count(),
            ],
            'controls' => [
                'total' => Control::query()->count(),
                'active' => Control::query()->where('status', 'active')->count(),
                'remediation_required' => Control::query()->where('status', 'remediation_required')->count(),
                'effective' => Control::query()->where('effectiveness', 'effective')->count(),
                'untested' => Control::query()->where('effectiveness', 'untested')->count(),
            ],
            'action_plans' => [
                'total' => ActionPlan::query()->count(),
                'open' => ActionPlan::query()->where('status', 'open')->count(),
                'in_progress' => ActionPlan::query()->where('status', 'in_progress')->count(),
                'blocked' => ActionPlan::query()->where('status', 'blocked')->count(),
                'critical' => ActionPlan::query()->where('priority', 'critical')->count(),
            ],
        ];
    }
}
