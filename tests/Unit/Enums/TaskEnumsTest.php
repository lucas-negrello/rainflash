<?php

use App\Enums\{TaskStatusEnum, TaskTypeEnum};

it('has expected values for TaskStatusEnum', function () {
    expect(TaskStatusEnum::OPEN->value)->toBe(0)
        ->and(TaskStatusEnum::IN_PROGRESS->value)->toBe(1)
        ->and(TaskStatusEnum::DONE->value)->toBe(2)
        ->and(TaskStatusEnum::BLOCKED->value)->toBe(3);
});

it('returns correct labels for TaskStatusEnum', function () {
    $map = [
        TaskStatusEnum::OPEN->value => 'Aberta',
        TaskStatusEnum::IN_PROGRESS->value => 'Em progresso',
        TaskStatusEnum::DONE->value => 'Finalizada',
        TaskStatusEnum::BLOCKED->value => 'Bloqueada',
    ];
    foreach (TaskStatusEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

it('has expected values for TaskTypeEnum', function () {
    expect(TaskTypeEnum::SUPPORT->value)->toBe(0)
        ->and(TaskTypeEnum::FEATURE->value)->toBe(1)
        ->and(TaskTypeEnum::TECH->value)->toBe(2)
        ->and(TaskTypeEnum::BUG->value)->toBe(3)
        ->and(TaskTypeEnum::OTHER->value)->toBe(99);
});

it('returns correct labels for TaskTypeEnum', function () {
    $map = [
        TaskTypeEnum::SUPPORT->value => 'Suporte',
        TaskTypeEnum::FEATURE->value => 'Feature',
        TaskTypeEnum::TECH->value => 'Tech',
        TaskTypeEnum::BUG->value => 'Bug',
        TaskTypeEnum::OTHER->value => 'Other',
    ];
    foreach (TaskTypeEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

