<?php

use App\Enums\{ProjectBillingModelEnum, ProjectStatusEnum, ProjectTypeEnum};

it('has expected values for ProjectTypeEnum', function () {
    expect(ProjectTypeEnum::PRODUCT->value)->toBe(0)
        ->and(ProjectTypeEnum::OUTSOURCING->value)->toBe(1)
        ->and(ProjectTypeEnum::CLIENT_INTERNAL->value)->toBe(99);
});

it('returns correct labels for ProjectTypeEnum', function () {
    $map = [
        ProjectTypeEnum::PRODUCT->value => 'Produto',
        ProjectTypeEnum::OUTSOURCING->value => 'Outsourcing',
        ProjectTypeEnum::CLIENT_INTERNAL->value => 'Interno do Cliente',
    ];
    foreach (ProjectTypeEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

it('has expected values for ProjectBillingModelEnum', function () {
    expect(ProjectBillingModelEnum::TNM->value)->toBe(0)
        ->and(ProjectBillingModelEnum::FIXED->value)->toBe(1)
        ->and(ProjectBillingModelEnum::INTERNAL->value)->toBe(99);
});

it('returns correct labels for ProjectBillingModelEnum', function () {
    $map = [
        ProjectBillingModelEnum::TNM->value => 'Tempo e Recursos',
        ProjectBillingModelEnum::FIXED->value => 'Preço Fixo',
        ProjectBillingModelEnum::INTERNAL->value => 'Interno',
    ];
    foreach (ProjectBillingModelEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

it('has expected values for ProjectStatusEnum', function () {
    expect(ProjectStatusEnum::INACTIVE->value)->toBe(0)
        ->and(ProjectStatusEnum::ACTIVE->value)->toBe(1)
        ->and(ProjectStatusEnum::PENDING->value)->toBe(2)
        ->and(ProjectStatusEnum::COMPLETED->value)->toBe(3);
});

it('returns correct labels for ProjectStatusEnum', function () {
    $map = [
        ProjectStatusEnum::INACTIVE->value => 'Inativo',
        ProjectStatusEnum::ACTIVE->value => 'Ativo',
        ProjectStatusEnum::PENDING->value => 'Pendente',
        ProjectStatusEnum::COMPLETED->value => 'Completo',
    ];
    foreach (ProjectStatusEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

