<?php

namespace Database\Factories;

use App\Models\{AuditLog, Company, CompanyUser, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'actor_user_id' => User::factory(),
            'actor_company_user_id' => CompanyUser::factory(),
            'action_key' => fake()->randomElement(['user.created','company.updated','role.assigned']),
            'subject_type' => fake()->randomElement([Company::class, User::class, CompanyUser::class]),
            'subject_id' => 1,
            'meta' => [],
        ];
    }
}

