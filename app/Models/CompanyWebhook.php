<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @use HasFactory<\Database\Factories\CompanyWebhookFactory> */
class CompanyWebhook extends Model
{
    use HasFactory;

    protected $table = 'company_webhooks';

    protected $fillable = [
        'company_id',
        'url',
        'secret',
        'active',
        'event_filters',
        'retry_policy',
        'meta',
    ];

    protected $casts = [
        'active' => 'boolean',
        'event_filters' => 'array',
        'retry_policy' => 'array',
        'meta' => 'array',
    ];

    public function deliveries(): HasMany
    {
        return $this->hasMany(WebhookDelivery::class, 'webhook_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
