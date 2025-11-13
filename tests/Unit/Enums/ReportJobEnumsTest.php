<?php

use App\Enums\{ReportJobStatusEnum, ReportJobTypeEnum};

it('covers ReportJobTypeEnum label and extension', function () {
    foreach (ReportJobTypeEnum::cases() as $case) {
        expect($case->label())->toBe(strtoupper($case->extension()));
    }
});

it('has expected numeric values for ReportJobTypeEnum', function () {
    expect(ReportJobTypeEnum::CSV->value)->toBe(0)
        ->and(ReportJobTypeEnum::PDF->value)->toBe(1)
        ->and(ReportJobTypeEnum::XLSX->value)->toBe(2);
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

it('has expected numeric values for ReportJobStatusEnum', function () {
    expect(ReportJobStatusEnum::PENDING->value)->toBe(0)
        ->and(ReportJobStatusEnum::PROCESSING->value)->toBe(1)
        ->and(ReportJobStatusEnum::DONE->value)->toBe(2)
        ->and(ReportJobStatusEnum::FAILED->value)->toBe(3);
});
