<?php

use App\Enums\{PtoRequestHoursOptionEnum, PtoRequestStatusEnum, PtoRequestTypeEnum};
use App\Models\{Company, CompanyUser, PtoApproval, PtoRequest};
use Illuminate\Support\Carbon;

it('creates PtoRequest and covers casts and relations', function () {
    $company = Company::factory()->create();
    $requester = CompanyUser::factory()->for($company)->create();
    $approver = CompanyUser::factory()->for($company)->create();

    $start = now()->addDays(10)->startOfDay();
    $end = (clone $start)->addDays(2)->endOfDay();

    $req = PtoRequest::factory()->create([
        'company_id' => $company->id,
        'company_user_id' => $requester->id,
        'approved_by_company_user_id' => $approver->id,
        'status' => PtoRequestStatusEnum::REQUESTED,
        'type' => PtoRequestTypeEnum::VACATION,
        'hours_option' => PtoRequestHoursOptionEnum::FULL_DAY,
        'start_date' => $start,
        'end_date' => $end,
        'requested_at' => now(),
        'reason' => 'descanso',
        'meta' => ['a' => 1],
    ]);

    expect($req->company->is($company))->toBeTrue()
        ->and($req->requestedBy->is($requester))->toBeTrue()
        ->and($req->approvedBy->is($approver))->toBeTrue()
        ->and($req->status)->toBe(PtoRequestStatusEnum::REQUESTED)
        ->and($req->type)->toBe(PtoRequestTypeEnum::VACATION)
        ->and($req->hours_option)->toBe(PtoRequestHoursOptionEnum::FULL_DAY)
        ->and($req->requested_at)->toBeInstanceOf(Carbon::class)
        ->and($req->meta)->toBeArray();
});

it('relates to PtoApproval', function () {
    $req = PtoRequest::factory()->create();
    $approval = PtoApproval::factory()->create(['pto_request_id' => $req->id]);

    expect($req->ptoApproval->is($approval))->toBeTrue();
});

