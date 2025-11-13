<?php

use App\Enums\CalendarEventTypeEnum;
use App\Models\{Calendar, CalendarEvent, Company};
use Illuminate\Support\Carbon;

it('creates CalendarEvent and covers casts and relations', function () {
    $company = Company::factory()->create();
    $calendar = Calendar::factory()->create(['company_id' => $company->id]);

    $date = now()->addDays(10)->startOfDay();

    $evt = CalendarEvent::factory()->create([
        'calendar_id' => $calendar->id,
        'date' => $date,
        'type' => CalendarEventTypeEnum::HOLIDAY,
        'hours' => 0,
        'note' => 'Feriado Nacional',
        'meta' => ['x' => 1],
    ]);

    expect($evt->calendar->is($calendar))->toBeTrue()
        ->and($evt->type)->toBe(CalendarEventTypeEnum::HOLIDAY)
        ->and($evt->date)->toBeInstanceOf(Carbon::class)
        ->and($evt->meta)->toBeArray();
});

