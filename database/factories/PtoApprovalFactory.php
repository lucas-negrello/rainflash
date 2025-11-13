<?php

namespace Database\Factories;

use App\Enums\PtoApprovalDecisionEnum;
use App\Models\{Company, CompanyUser, PtoApproval, PtoRequest};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PtoApproval>
 */
class PtoApprovalFactory extends Factory
{
    protected $model = PtoApproval::class;

    public function definition(): array
    {
        return [
            'pto_request_id' => PtoRequest::factory()->for(Company::factory(), 'company')->create()->id,
            'approver_company_user_id' => CompanyUser::factory(),
            'decision' => PtoApprovalDecisionEnum::APPROVED,
            'decided_at' => now(),
            'note' => fake()->optional()->sentence(),
            'meta' => [],
        ];
    }
}

