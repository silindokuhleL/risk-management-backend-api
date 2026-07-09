<?php

namespace Tests\Feature;

use App\Models\Control;
use App\Models\Risk;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActionPlanApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_action_plans(): void
    {
        $this->getJson('/api/action-plans')->assertUnauthorized();
    }

    public function test_user_without_action_plan_permission_cannot_access_action_plans(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/action-plans')
            ->assertForbidden();
    }

    public function test_admin_can_manage_action_plan_workflow(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $risk = Risk::query()->create([
            'owner_id' => $admin->id,
            'title' => 'Evidence gaps during audit',
            'description' => 'Audit evidence may not be complete before control review.',
            'category' => 'Compliance',
            'inherent_likelihood' => 3,
            'inherent_impact' => 5,
            'residual_likelihood' => 2,
            'residual_impact' => 3,
            'status' => 'open',
            'identified_at' => '2026-07-01',
        ]);

        $control = Control::query()->create([
            'risk_id' => $risk->id,
            'owner_id' => $admin->id,
            'title' => 'Evidence completeness review',
            'description' => 'Confirm evidence exists before sign-off.',
            'type' => 'Detective',
            'effectiveness' => 'untested',
            'status' => 'active',
            'due_at' => '2026-08-01',
        ]);

        $payload = [
            'risk_id' => $risk->id,
            'control_id' => $control->id,
            'owner_id' => $admin->id,
            'title' => 'Collect missing audit evidence',
            'description' => 'Request missing documents and attach them to the control record.',
            'priority' => 'high',
            'status' => 'open',
            'due_at' => '2026-08-15',
        ];

        $createResponse = $this->actingAs($admin)
            ->postJson('/api/action-plans', $payload)
            ->assertCreated()
            ->assertJsonPath('data.title', 'Collect missing audit evidence')
            ->assertJsonPath('data.risk.id', $risk->id)
            ->assertJsonPath('data.control.id', $control->id)
            ->assertJsonPath('data.owner.id', $admin->id)
            ->assertJsonPath('data.priority', 'high');

        $actionPlanId = $createResponse->json('data.id');

        $this->actingAs($admin)
            ->getJson('/api/action-plans?priority=high')
            ->assertOk()
            ->assertJsonPath('data.0.id', $actionPlanId)
            ->assertJsonPath('meta.total', 1);

        $this->actingAs($admin)
            ->getJson("/api/action-plans/{$actionPlanId}")
            ->assertOk()
            ->assertJsonPath('data.id', $actionPlanId)
            ->assertJsonPath('data.risk.title', 'Evidence gaps during audit')
            ->assertJsonPath('data.control.title', 'Evidence completeness review');

        $this->actingAs($admin)
            ->patchJson("/api/action-plans/{$actionPlanId}", [
                'status' => 'completed',
                'completed_at' => '2026-07-09',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'completed')
            ->assertJsonPath('data.completed_at', '2026-07-09');

        $this->actingAs($admin)
            ->deleteJson("/api/action-plans/{$actionPlanId}")
            ->assertNoContent();

        $this->assertDatabaseMissing('action_plans', ['id' => $actionPlanId]);
    }
}
