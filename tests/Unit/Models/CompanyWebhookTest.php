<?php

use App\Models\{Company, CompanyWebhook, WebhookDelivery};

it('creates CompanyWebhook and covers casts and relations', function () {
    $company = Company::factory()->create();

    $wh = CompanyWebhook::factory()->create([
        'company_id' => $company->id,
        'event_filters' => ['user.created'],
        'retry_policy' => ['max_attempts' => 3],
        'meta' => ['a' => 1],
    ]);

    WebhookDelivery::factory()->count(2)->create(['webhook_id' => $wh->id]);

    expect($wh->company->is($company))->toBeTrue()
        ->and($wh->deliveries()->count())->toBe(2)
        ->and($wh->active)->toBeTrue()
        ->and($wh->event_filters)->toBeArray()
        ->and($wh->retry_policy)->toBeArray()
        ->and($wh->meta)->toBeArray();
});

