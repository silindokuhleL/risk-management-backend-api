<?php

namespace Database\Seeders;

use App\Models\Control;
use App\Models\Risk;
use App\Models\User;
use Illuminate\Database\Seeder;

class ControlSeeder extends Seeder
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

        if ($accessRisk) {
            Control::query()->updateOrCreate(
                ['title' => 'Quarterly role and permission review'],
                [
                    'risk_id' => $accessRisk->id,
                    'owner_id' => $superAdmin?->id,
                    'description' => 'Review admin and super-admin access quarterly to confirm least-privilege access.',
                    'type' => 'Preventive',
                    'effectiveness' => 'effective',
                    'status' => 'active',
                    'due_at' => now()->addDays(45)->toDateString(),
                    'tested_at' => now()->subDays(14)->toDateString(),
                ]
            );
        }

        if ($followUpRisk) {
            Control::query()->updateOrCreate(
                ['title' => 'Weekly overdue action review'],
                [
                    'risk_id' => $followUpRisk->id,
                    'owner_id' => $admin?->id,
                    'description' => 'Review open risk actions every week and escalate overdue items to the accountable owner.',
                    'type' => 'Detective',
                    'effectiveness' => 'partially_effective',
                    'status' => 'remediation_required',
                    'due_at' => now()->addDays(7)->toDateString(),
                    'tested_at' => now()->subDays(3)->toDateString(),
                ]
            );
        }

        if ($evidenceRisk) {
            Control::query()->updateOrCreate(
                ['title' => 'Evidence attachment completeness check'],
                [
                    'risk_id' => $evidenceRisk->id,
                    'owner_id' => $admin?->id,
                    'description' => 'Require supporting evidence before controls can be marked complete.',
                    'type' => 'Corrective',
                    'effectiveness' => 'untested',
                    'status' => 'draft',
                    'due_at' => now()->addDays(21)->toDateString(),
                    'tested_at' => null,
                ]
            );
        }
    }
}
