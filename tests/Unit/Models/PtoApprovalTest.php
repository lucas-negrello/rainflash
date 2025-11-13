<?php

use App\Enums\PtoApprovalDecisionEnum;
use App\Models\{Company, CompanyUser, PtoApproval, PtoRequest};
use Illuminate\Support\Carbon;

it('creates PtoApproval and covers casts and relations', function () {
    $company = Company::factory()->create();
    $requester = CompanyUser::factory()->for($company)->create();
    $req = PtoRequest::factory()->create([
        'company_id' => $company->id,
        'company_user_id' => $requester->id,
    ]);

    $approver = CompanyUser::factory()->for($company)->create();

    $ap = PtoApproval::factory()->create([
        'pto_request_id' => $req->id,
        'approver_company_user_id' => $approver->id,
        'decision' => PtoApprovalDecisionEnum::APPROVED,
        'decided_at' => now(),
        'meta' => ['x' => 1],
    ]);

    expect($ap->ptoRequest->is($req))->toBeTrue()
        ->and($ap->approverCompanyUser->is($approver))->toBeTrue()
        ->and($ap->decision)->toBe(PtoApprovalDecisionEnum::APPROVED)
        ->and($ap->decided_at)->toBeInstanceOf(Carbon::class)
        ->and($ap->meta)->toBeArray();
});

