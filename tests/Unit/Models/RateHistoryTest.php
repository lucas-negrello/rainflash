<?php

use App\Models\{Company, CompanyUser, RateHistory};
use Illuminate\Support\Carbon;

it('casts and relation on RateHistory', function () {
    $companyUser = CompanyUser::factory()->for(Company::factory())->create();

    $rh = RateHistory::factory()->create([
        'company_user_id' => $companyUser->id,
        'effective_from' => now()->subMonth(),
        'effective_to' => now()->addMonth(),
        'hour_cost' => 123.45,
        'currency' => 'USD',
        'meta' => ['z' => 1],
    ]);

    expect($rh->effective_from)->toBeInstanceOf(Carbon::class)
        ->and($rh->companyUser->is($companyUser))->toBeTrue()
        ->and($rh->hour_cost)->toBeFloat();
});

