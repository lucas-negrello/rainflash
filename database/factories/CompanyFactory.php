<?php

namespace Database\Factories;

use App\Enums\CompanyStatusEnum;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name.'-'.fake()->unique()->bothify('###')),
            'status' => CompanyStatusEnum::ACTIVE,
            'meta' => [],
        ];
    }

    public function trial(): static
    {
        return $this->state(fn () => ['status' => CompanyStatusEnum::TRIAL]);
    }

    public function suspended(): static
    {
        return $this->state(fn () => ['status' => CompanyStatusEnum::SUSPENDED]);
    }
}

