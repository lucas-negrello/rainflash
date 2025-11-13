<?php

use App\Enums\WebhookDeliveryStatusEnum;
use App\Models\{Company, CompanyWebhook, WebhookDelivery};
use Illuminate\Support\Carbon;

it('creates WebhookDelivery and covers casts and relations', function () {
    $company = Company::factory()->create();
    $wh = CompanyWebhook::factory()->create(['company_id' => $company->id]);

    $deliv = WebhookDelivery::factory()->sent()->create([
        'webhook_id' => $wh->id,
        'event_key' => 'user.created',
        'payload_snipped' => ['id' => 'abc'],
        'attempt_count' => 2,
        'meta' => ['x' => 1],
    ]);

    expect($deliv->webhook->is($wh))->toBeTrue()
        ->and($deliv->status)->toBe(WebhookDeliveryStatusEnum::SENT)
        ->and($deliv->delivered_at)->toBeInstanceOf(Carbon::class)
        ->and($deliv->payload_snipped)->toBeArray()
        ->and($deliv->meta)->toBeArray();
});

