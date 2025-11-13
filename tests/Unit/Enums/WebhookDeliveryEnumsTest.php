<?php

use App\Enums\WebhookDeliveryStatusEnum;

it('WebhookDeliveryStatusEnum labels', function () {
    $expected = [
        WebhookDeliveryStatusEnum::PENDING->value => 'Pendente',
        WebhookDeliveryStatusEnum::SENT->value => 'Enviado',
        WebhookDeliveryStatusEnum::FAILED->value => 'Falha',
    ];

    foreach (WebhookDeliveryStatusEnum::cases() as $case) {
        expect($case->label())->toBe($expected[$case->value]);
    }
});

it('has expected numeric values for WebhookDeliveryStatusEnum', function () {
    expect(WebhookDeliveryStatusEnum::PENDING->value)->toBe(0)
        ->and(WebhookDeliveryStatusEnum::SENT->value)->toBe(1)
        ->and(WebhookDeliveryStatusEnum::FAILED->value)->toBe(2);
});
