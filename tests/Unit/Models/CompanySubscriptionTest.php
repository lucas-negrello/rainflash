<?php

use App\Enums\CompanySubscriptionStatusEnum;
use App\Models\{CompanySubscription, Company, Plan};
use Database\Factories\{CompanySubscriptionFactory};
use Illuminate\Support\Carbon;

it('creates CompanySubscription with casts and relations', function () {
    $sub = CompanySubscriptionFactory::new()->create();

    expect($sub->plan)->toBeInstanceOf(Plan::class)
        ->and($sub->company)->toBeInstanceOf(Company::class)
        ->and($sub->status)->toBe(CompanySubscriptionStatusEnum::ACTIVE)
        ->and($sub->period_start)->toBeInstanceOf(Carbon::class)
        ->and($sub->period_end)->toBeInstanceOf(Carbon::class)
        ->and($sub->trial_end)->toBeNull()
        ->and($sub->meta)->toBeArray();
});

it('creates trial CompanySubscription', function () {
    $trial = CompanySubscriptionFactory::new()->trial()->create();

    expect($trial->status)->toBe(CompanySubscriptionStatusEnum::TRIAL)
        ->and($trial->trial_end)->toBeInstanceOf(Carbon::class);
});
