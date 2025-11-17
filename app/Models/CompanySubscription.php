<?php

namespace App\Models;

use App\Enums\CompanySubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySubscription extends Model
{
    use HasFactory;

    protected $table = 'company_subscriptions';

    protected $fillable = [
        'company_id',
        'plan_id',
        'status',
        'seats_limit',
        'period_start',
        'period_end',
        'trial_end',
        'meta',
    ];

    protected $casts = [
        'status' => CompanySubscriptionStatusEnum::class,
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'trial_end' => 'datetime',
        'meta' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
