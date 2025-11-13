<?php

use App\Enums\{ReportJobStatusEnum, ReportJobTypeEnum};

it('covers ReportJobTypeEnum label and extension', function () {
    foreach (ReportJobTypeEnum::cases() as $case) {
        expect($case->label())->toBe(strtoupper($case->extension()));
    }
});

it('covers ReportJobStatusEnum labels', function () {
    $mapByValue = [
        ReportJobStatusEnum::PENDING->value => 'Pendente',
        ReportJobStatusEnum::PROCESSING->value => 'Processando',
        ReportJobStatusEnum::DONE->value => 'Concluído',
        ReportJobStatusEnum::FAILED->value => 'Falha',
    ];

    foreach (ReportJobStatusEnum::cases() as $case) {
        expect($case->label())->toBe($mapByValue[$case->value]);
    }
});
