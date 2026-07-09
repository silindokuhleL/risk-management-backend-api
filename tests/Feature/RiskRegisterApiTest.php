<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskRegisterApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_risk_register(): void
    {
        $this->getJson('/api/risks')->assertUnauthorized();
    }

    public function test_user_without_risk_permission_cannot_access_risk_register(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/risks')
            ->assertForbidden();
    }

    public function test_admin_can_manage_risk_register_workflow(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $payload = [
            'owner_id' => $admin->id,
            'title' => 'Supplier outage affects month-end reporting',
            'description' => 'Critical supplier downtime may delay month-end risk reporting.',
            'category' => 'Operational',
            'inherent_likelihood' => 4,
            'inherent_impact' => 5,
            'residual_likelihood' => 2,
            'residual_impact' => 3,
            'status' => 'open',
            'identified_at' => '2026-07-01',
        ];

        $createResponse = $this->actingAs($admin)
            ->postJson('/api/risks', $payload)
            ->assertCreated()
            ->assertJsonPath('data.title', 'Supplier outage affects month-end reporting')
            ->assertJsonPath('data.inherent_score', 20)
            ->assertJsonPath('data.residual_score', 6)
            ->assertJsonPath('data.owner.id', $admin->id);

        $riskId = $createResponse->json('data.id');

        $this->actingAs($admin)
            ->getJson('/api/risks')
            ->assertOk()
            ->assertJsonPath('data.0.id', $riskId)
            ->assertJsonPath('meta.total', 1);

        $this->actingAs($admin)
            ->getJson("/api/risks/{$riskId}")
            ->assertOk()
            ->assertJsonPath('data.id', $riskId)
            ->assertJsonPath('data.category', 'Operational');

        $this->actingAs($admin)
            ->patchJson("/api/risks/{$riskId}", [
                'status' => 'mitigating',
                'reviewed_at' => '2026-07-09',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'mitigating')
            ->assertJsonPath('data.reviewed_at', '2026-07-09');

        $this->actingAs($admin)
            ->deleteJson("/api/risks/{$riskId}")
            ->assertNoContent();

        $this->assertDatabaseMissing('risks', ['id' => $riskId]);
    }
}
