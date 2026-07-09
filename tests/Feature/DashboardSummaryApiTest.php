<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ActionPlanSeeder;
use Database\Seeders\ControlSeeder;
use Database\Seeders\RiskSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardSummaryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard_summary(): void
    {
        $this->getJson('/api/dashboard/summary')->assertUnauthorized();
    }

    public function test_user_without_dashboard_permission_cannot_access_dashboard_summary(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/dashboard/summary')
            ->assertForbidden();
    }

    public function test_admin_can_view_dashboard_summary_counts(): void
    {
        $this->seed([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            RiskSeeder::class,
            ControlSeeder::class,
            ActionPlanSeeder::class,
        ]);

        $admin = User::query()->where('email', 'Luyanda@gmail.com')->firstOrFail();

        $this->actingAs($admin)
            ->getJson('/api/dashboard/summary')
            ->assertOk()
            ->assertJsonPath('data.risks.total', 3)
            ->assertJsonPath('data.risks.open', 1)
            ->assertJsonPath('data.risks.mitigating', 1)
            ->assertJsonPath('data.risks.monitoring', 1)
            ->assertJsonPath('data.risks.high_inherent', 1)
            ->assertJsonPath('data.controls.total', 3)
            ->assertJsonPath('data.controls.active', 1)
            ->assertJsonPath('data.controls.remediation_required', 1)
            ->assertJsonPath('data.controls.effective', 1)
            ->assertJsonPath('data.controls.untested', 1)
            ->assertJsonPath('data.action_plans.total', 3)
            ->assertJsonPath('data.action_plans.open', 1)
            ->assertJsonPath('data.action_plans.in_progress', 1)
            ->assertJsonPath('data.action_plans.blocked', 1)
            ->assertJsonPath('data.action_plans.critical', 1);
    }
}
