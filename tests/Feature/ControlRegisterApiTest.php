<?php

namespace Tests\Feature;

use App\Models\Risk;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControlRegisterApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_control_register(): void
    {
        $this->getJson('/api/controls')->assertUnauthorized();
    }

    public function test_user_without_control_permission_cannot_access_control_register(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/controls')
            ->assertForbidden();
    }

    public function test_admin_can_manage_control_register_workflow(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $risk = Risk::query()->create([
            'owner_id' => $admin->id,
            'title' => 'Customer data exposure',
            'description' => 'Sensitive customer records may be exposed.',
            'category' => 'Security',
            'inherent_likelihood' => 4,
            'inherent_impact' => 5,
            'residual_likelihood' => 2,
            'residual_impact' => 4,
            'status' => 'open',
            'identified_at' => '2026-07-01',
        ]);

        $payload = [
            'risk_id' => $risk->id,
            'owner_id' => $admin->id,
            'title' => 'Monthly access review',
            'description' => 'Confirm all users still need their assigned permissions.',
            'type' => 'Preventive',
            'effectiveness' => 'untested',
            'status' => 'active',
            'due_at' => '2026-08-01',
        ];

        $createResponse = $this->actingAs($admin)
            ->postJson('/api/controls', $payload)
            ->assertCreated()
            ->assertJsonPath('data.title', 'Monthly access review')
            ->assertJsonPath('data.risk.id', $risk->id)
            ->assertJsonPath('data.owner.id', $admin->id)
            ->assertJsonPath('data.effectiveness', 'untested');

        $controlId = $createResponse->json('data.id');

        $this->actingAs($admin)
            ->getJson('/api/controls?status=active')
            ->assertOk()
            ->assertJsonPath('data.0.id', $controlId)
            ->assertJsonPath('meta.total', 1);

        $this->actingAs($admin)
            ->getJson("/api/controls/{$controlId}")
            ->assertOk()
            ->assertJsonPath('data.id', $controlId)
            ->assertJsonPath('data.risk.title', 'Customer data exposure');

        $this->actingAs($admin)
            ->patchJson("/api/controls/{$controlId}", [
                'effectiveness' => 'effective',
                'tested_at' => '2026-07-09',
            ])
            ->assertOk()
            ->assertJsonPath('data.effectiveness', 'effective')
            ->assertJsonPath('data.tested_at', '2026-07-09');

        $this->actingAs($admin)
            ->deleteJson("/api/controls/{$controlId}")
            ->assertNoContent();

        $this->assertDatabaseMissing('controls', ['id' => $controlId]);
    }
}
