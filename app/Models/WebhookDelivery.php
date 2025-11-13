<?php

namespace App\Models;

use App\Enums\WebhookDeliveryStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @use HasFactory<\Database\Factories\WebhookDeliveryFactory> */
class WebhookDelivery extends Model
{
    use HasFactory;

    protected $table = 'webhook_deliveries';

    protected $fillable = [
        'webhook_id',
        'event_key',
        'payload_snipped',
        'status',
        'attempt_count',
        'last_error',
        'delivered_at',
        'meta',
    ];

    protected $casts = [
        'payload_snipped' => 'array',
        'status' => WebhookDeliveryStatusEnum::class,
        'meta' => 'array',
        'delivered_at' => 'datetime',
    ];

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(CompanyWebhook::class, 'webhook_id');
    }
}
