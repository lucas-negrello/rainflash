<?php

use App\Enums\CalendarScopeEnum;
use App\Models\{Calendar, CalendarEvent, Company};

it('creates Calendar and covers casts and relations', function () {
    $company = Company::factory()->create();
    $cal = Calendar::factory()->create([
        'company_id' => $company->id,
        'scope' => CalendarScopeEnum::COMPANY,
        'meta' => ['a' => 1],
    ]);

    CalendarEvent::factory()->count(2)->create(['calendar_id' => $cal->id]);

    expect($cal->company->is($company))->toBeTrue()
        ->and($cal->events()->count())->toBe(2)
        ->and($cal->scope)->toBe(CalendarScopeEnum::COMPANY);
});

