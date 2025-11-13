<?php

use App\Enums\{ReportJobStatusEnum, ReportJobTypeEnum};
use App\Models\{Company, CompanyUser, ReportJob};

it('creates ReportJob and loads relations and casts', function () {
    $companyUser = CompanyUser::factory()->for(Company::factory())->create();

    $job = ReportJob::factory()->create([
        'company_id' => $companyUser->company_id,
        'requested_by_company_user_id' => $companyUser->id,
        'type' => ReportJobTypeEnum::CSV,
        'status' => ReportJobStatusEnum::PENDING,
        'parameters' => ['foo' => 'bar'],
    ]);

    expect($job->company->id)->toBe($companyUser->company_id)
        ->and($job->requestedByCompanyUser->is($companyUser))->toBeTrue()
        ->and($job->type)->toBe(ReportJobTypeEnum::CSV)
        ->and($job->status)->toBe(ReportJobStatusEnum::PENDING)
        ->and($job->parameters)->toBeArray();
});

