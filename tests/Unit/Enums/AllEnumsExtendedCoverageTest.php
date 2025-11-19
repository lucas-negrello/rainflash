<?php

use App\Enums\CalendarEventTypeEnum;
use App\Enums\CalendarScopeEnum;
use App\Enums\CompanyStatusEnum;
use App\Enums\CompanySubscriptionStatusEnum;
use App\Enums\FeatureTypeEnum;
use App\Enums\NavigationGroupEnum;
use App\Enums\ProjectBillingModelEnum;
use App\Enums\ProjectStatusEnum;
use App\Enums\ProjectTypeEnum;
use App\Enums\PtoApprovalDecisionEnum;
use App\Enums\PtoRequestHoursOptionEnum;
use App\Enums\PtoRequestStatusEnum;
use App\Enums\PtoRequestTypeEnum;
use App\Enums\ReportJobStatusEnum;
use App\Enums\ReportJobTypeEnum;
use App\Enums\ResourceProfileSeniorityEnum;
use App\Enums\RoleScopeEnum;
use App\Enums\TaskStatusEnum;
use App\Enums\TaskTypeEnum;
use App\Enums\TimeEntryOriginEnum;
use App\Enums\TimeEntryStatusEnum;
use App\Enums\UserSkillProficiencyLevelEnum;
use App\Enums\WebhookDeliveryStatusEnum;
use App\Enums\WorkScheduleWeekdayEnum;

$enumClasses = [
    CalendarEventTypeEnum::class,
    CalendarScopeEnum::class,
    CompanyStatusEnum::class,
    CompanySubscriptionStatusEnum::class,
    FeatureTypeEnum::class,
    NavigationGroupEnum::class,
    ProjectBillingModelEnum::class,
    ProjectStatusEnum::class,
    ProjectTypeEnum::class,
    PtoApprovalDecisionEnum::class,
    PtoRequestHoursOptionEnum::class,
    PtoRequestStatusEnum::class,
    PtoRequestTypeEnum::class,
    ReportJobStatusEnum::class,
    ReportJobTypeEnum::class,
    ResourceProfileSeniorityEnum::class,
    RoleScopeEnum::class,
    TaskStatusEnum::class,
    TaskTypeEnum::class,
    TimeEntryOriginEnum::class,
    TimeEntryStatusEnum::class,
    UserSkillProficiencyLevelEnum::class,
    WebhookDeliveryStatusEnum::class,
    WorkScheduleWeekdayEnum::class,
];

it('static colors matches per-case color() for all enums', function () use ($enumClasses) {
    foreach ($enumClasses as $class) {
        $expected = [];
        foreach ($class::cases() as $case) {
            $expected[$case->value] = $case->color();
        }
        expect($class::colors())->toEqual($expected);
    }
});

it('fromValue resolves first case for all enums', function () use ($enumClasses) {
    foreach ($enumClasses as $class) {
        $first = $class::cases()[0];
        expect($class::fromValue($first->value))->toBe($first);
    }
});

it('dropdownOptions, labels and toSelectOptions are equivalent for all enums', function () use ($enumClasses) {
    foreach ($enumClasses as $class) {
        expect($class::dropdownOptions())->toEqual($class::labels())->toEqual($class::toSelectOptions());
    }
});

it('map helper returns labels list for all enums', function () use ($enumClasses) {
    foreach ($enumClasses as $class) {
        $viaMap = $class::map(fn($c) => $c->label());
        expect($viaMap)->toEqual(array_values($class::labels()));
    }
});

it('fromValue throws for invalid value on a numeric enum', function () {
    expect(fn () => FeatureTypeEnum::fromValue(999))->toThrow(InvalidArgumentException::class);
});

it('fromValue throws for invalid value on a string enum', function () {
    expect(fn () => NavigationGroupEnum::fromValue('Inexistente'))->toThrow(InvalidArgumentException::class);
});

