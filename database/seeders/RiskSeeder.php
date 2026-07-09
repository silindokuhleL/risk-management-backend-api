<?php

namespace Database\Seeders;

use App\Models\Risk;
use App\Models\User;
use Illuminate\Database\Seeder;

class RiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('email', 'Luyanda@gmail.com')->first();
        $superAdmin = User::query()->where('email', 'Sinokuhle@gmail.com')->first();

        Risk::query()->updateOrCreate(
            ['title' => 'Unauthorised access to risk data'],
            [
                'owner_id' => $superAdmin?->id,
                'description' => 'Sensitive risk records may be exposed if role-based access is not enforced consistently.',
                'category' => 'Security',
                'inherent_likelihood' => 4,
                'inherent_impact' => 5,
                'residual_likelihood' => 2,
                'residual_impact' => 4,
                'status' => 'mitigating',
                'identified_at' => now()->subDays(30)->toDateString(),
                'reviewed_at' => now()->subDays(5)->toDateString(),
            ]
        );

        Risk::query()->updateOrCreate(
            ['title' => 'Delayed action plan follow-up'],
            [
                'owner_id' => $admin?->id,
                'description' => 'Open risk actions may not be reviewed in time, increasing operational exposure.',
                'category' => 'Operational',
                'inherent_likelihood' => 3,
                'inherent_impact' => 4,
                'residual_likelihood' => 2,
                'residual_impact' => 3,
                'status' => 'monitoring',
                'identified_at' => now()->subDays(18)->toDateString(),
                'reviewed_at' => now()->subDays(2)->toDateString(),
            ]
        );

        Risk::query()->updateOrCreate(
            ['title' => 'Incomplete control evidence'],
            [
                'owner_id' => $admin?->id,
                'description' => 'Controls may be marked as complete without enough supporting evidence.',
                'category' => 'Compliance',
                'inherent_likelihood' => 3,
                'inherent_impact' => 5,
                'residual_likelihood' => 2,
                'residual_impact' => 4,
                'status' => 'open',
                'identified_at' => now()->subDays(10)->toDateString(),
                'reviewed_at' => null,
            ]
        );
    }
}
