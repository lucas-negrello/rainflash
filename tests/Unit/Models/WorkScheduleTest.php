<?php

use App\Enums\WorkScheduleWeekdayEnum;
use App\Models\{Company, CompanyUser, WorkSchedule};
use Illuminate\Support\Carbon;

it('casts and relation on WorkSchedule', function () {
    $companyUser = CompanyUser::factory()->for(Company::factory())->create();

    $ws = WorkSchedule::factory()->create([
        'company_user_id' => $companyUser->id,
        'weekday' => WorkScheduleWeekdayEnum::FRIDAY,
        'effective_from' => now()->startOfWeek(),
        'effective_to' => now()->endOfWeek(),
        'daily_hours' => 8.0,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'meta' => ['x' => 'y'],
    ]);

    expect($ws->weekday)->toBe(WorkScheduleWeekdayEnum::FRIDAY)
        ->and($ws->effective_from)->toBeInstanceOf(Carbon::class)
        ->and($ws->companyUser->is($companyUser))->toBeTrue();
});

