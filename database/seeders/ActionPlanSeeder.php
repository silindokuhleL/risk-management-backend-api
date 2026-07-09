<?php

namespace Database\Seeders;

use App\Models\ActionPlan;
use App\Models\Control;
use App\Models\Risk;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('email', 'Luyanda@gmail.com')->first();
        $superAdmin = User::query()->where('email', 'Sinokuhle@gmail.com')->first();

        $accessRisk = Risk::query()->where('title', 'Unauthorised access to risk data')->first();
        $followUpRisk = Risk::query()->where('title', 'Delayed action plan follow-up')->first();
        $evidenceRisk = Risk::query()->where('title', 'Incomplete control evidence')->first();

        $roleReview = Control::query()->where('title', 'Quarterly role and permission review')->first();
        $overdueReview = Control::query()->where('title', 'Weekly overdue action review')->first();
        $evidenceCheck = Control::query()->where('title', 'Evidence attachment completeness check')->first();

        if ($accessRisk) {
            ActionPlan::query()->updateOrCreate(
                ['title' => 'Automate stale permission alerts'],
                [
                    'risk_id' => $accessRisk->id,
                    'control_id' => $roleReview?->id,
                    'owner_id' => $superAdmin?->id,
                    'description' => 'Notify administrators when privileged access has not been reviewed within the expected cycle.',
                    'priority' => 'high',
                    'status' => 'in_progress',
                    'due_at' => now()->addDays(30)->toDateString(),
                    'completed_at' => null,
                ]
            );
        }

        if ($followUpRisk) {
            ActionPlan::query()->updateOrCreate(
                ['title' => 'Escalate overdue risk actions'],
                [
                    'risk_id' => $followUpRisk->id,
                    'control_id' => $overdueReview?->id,
                    'owner_id' => $admin?->id,
                    'description' => 'Send overdue action summaries to accountable owners before weekly risk review.',
                    'priority' => 'critical',
                    'status' => 'open',
                    'due_at' => now()->addDays(10)->toDateString(),
                    'completed_at' => null,
                ]
            );
        }

        if ($evidenceRisk) {
            ActionPlan::query()->updateOrCreate(
                ['title' => 'Add evidence upload requirement'],
                [
                    'risk_id' => $evidenceRisk->id,
                    'control_id' => $evidenceCheck?->id,
                    'owner_id' => $admin?->id,
                    'description' => 'Block completion until the user uploads or links evidence for the control activity.',
                    'priority' => 'medium',
                    'status' => 'blocked',
                    'due_at' => now()->addDays(21)->toDateString(),
                    'completed_at' => null,
                ]
            );
        }
    }
}
