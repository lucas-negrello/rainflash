<?php

use App\Enums\{PtoRequestTypeEnum, PtoRequestHoursOptionEnum, PtoRequestStatusEnum, PtoApprovalDecisionEnum};

it('PtoRequestTypeEnum labels', function () {
    $expected = [
        PtoRequestTypeEnum::VACATION->value => 'Férias',
        PtoRequestTypeEnum::SICKNESS->value => 'Doença',
        PtoRequestTypeEnum::PTO->value => 'PTO',
        PtoRequestTypeEnum::UNPAID->value => 'Não Remunerado',
        PtoRequestTypeEnum::OTHER->value => 'Outro',
    ];

    foreach (PtoRequestTypeEnum::cases() as $case) {
        expect($case->label())->toBe($expected[$case->value]);
    }
});

it('PtoRequestHoursOptionEnum labels', function () {
    $expected = [
        PtoRequestHoursOptionEnum::FULL_DAY->value => 'Dia Inteiro',
        PtoRequestHoursOptionEnum::HALF_DAY->value => 'Meio Dia',
        PtoRequestHoursOptionEnum::CUSTOM_HOURS->value => 'Horas Personalizadas',
    ];

    foreach (PtoRequestHoursOptionEnum::cases() as $case) {
        expect($case->label())->toBe($expected[$case->value]);
    }
});

it('PtoRequestStatusEnum labels', function () {
    $expected = [
        PtoRequestStatusEnum::REQUESTED->value => 'Solicitado',
        PtoRequestStatusEnum::APPROVED->value => 'Aprovado',
        PtoRequestStatusEnum::REPROVED->value => 'Rejeitado',
        PtoRequestStatusEnum::CANCELED->value => 'Cancelado',
    ];

    foreach (PtoRequestStatusEnum::cases() as $case) {
        expect($case->label())->toBe($expected[$case->value]);
    }
});

it('PtoApprovalDecisionEnum labels', function () {
    $expected = [
        PtoApprovalDecisionEnum::REJECTED->value => 'Rejeitado',
        PtoApprovalDecisionEnum::APPROVED->value => 'Aprovado',
    ];

    foreach (PtoApprovalDecisionEnum::cases() as $case) {
        expect($case->label())->toBe($expected[$case->value]);
    }
});

it('has expected numeric values for PtoRequestTypeEnum', function () {
    expect(PtoRequestTypeEnum::VACATION->value)->toBe(0)
        ->and(PtoRequestTypeEnum::SICKNESS->value)->toBe(1)
        ->and(PtoRequestTypeEnum::PTO->value)->toBe(2)
        ->and(PtoRequestTypeEnum::UNPAID->value)->toBe(3)
        ->and(PtoRequestTypeEnum::OTHER->value)->toBe(4);
});

it('has expected numeric values for PtoRequestHoursOptionEnum', function () {
    expect(PtoRequestHoursOptionEnum::FULL_DAY->value)->toBe(0)
        ->and(PtoRequestHoursOptionEnum::HALF_DAY->value)->toBe(1)
        ->and(PtoRequestHoursOptionEnum::CUSTOM_HOURS->value)->toBe(2);
});

it('has expected numeric values for PtoRequestStatusEnum', function () {
    expect(PtoRequestStatusEnum::REQUESTED->value)->toBe(0)
        ->and(PtoRequestStatusEnum::APPROVED->value)->toBe(1)
        ->and(PtoRequestStatusEnum::REPROVED->value)->toBe(2)
        ->and(PtoRequestStatusEnum::CANCELED->value)->toBe(3);
});

it('has expected numeric values for PtoApprovalDecisionEnum', function () {
    expect(PtoApprovalDecisionEnum::REJECTED->value)->toBe(0)
        ->and(PtoApprovalDecisionEnum::APPROVED->value)->toBe(1);
});
