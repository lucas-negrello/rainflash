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

