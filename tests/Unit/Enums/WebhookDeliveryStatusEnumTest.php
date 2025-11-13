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

