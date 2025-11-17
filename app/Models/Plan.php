<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'key',
        'name',
        'price_monthly',
        'currency',
        'meta',
    ];

    protected $casts = [
        'price_monthly' => 'float',
        'meta' => 'array',
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'plan_features')
                    ->withPivot('value', 'meta')
                    ->withTimestamps();
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_subscriptions')
                    ->withPivot('status', 'seats_limit', 'period_start', 'period_end', 'trial_end', 'meta')
                    ->withTimestamps();
    }

    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function companySubscriptions(): HasMany
    {
        return $this->hasMany(CompanySubscription::class);
    }
}
