<?php

namespace App\Models;

use App\Enums\CompanyStatusEnum;
use App\Enums\CompanySubscriptionStatusEnum;
use App\Enums\FeatureTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/** @use HasFactory<\Database\Factories\CompanyFactory> */
class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'meta',
        'current_plan_id',
        'subscription_status',
        'subscription_seats_limit',
        'subscription_period_start',
        'subscription_period_end',
        'subscription_trial_end',
        'subscription_meta',
    ];

    protected $casts = [
        'status' => CompanyStatusEnum::class,
        'meta' => 'array',
        'subscription_status' => CompanySubscriptionStatusEnum::class,
        'subscription_period_start' => 'datetime',
        'subscription_period_end' => 'datetime',
        'subscription_trial_end' => 'datetime',
        'subscription_meta' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user', 'company_id', 'user_id')
            ->withPivot([
                'primary_title',
                'currency',
                'active',
                'joined_at',
                'left_at',
                'meta',
            ])
            ->withTimestamps();
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function reportJobs(): HasMany
    {
        return $this->hasMany(ReportJob::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function ptoRequests(): HasMany
    {
        return $this->hasMany(PtoRequest::class);
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(CompanyWebhook::class);
    }

    public function calendars(): HasMany
    {
        return $this->hasMany(Calendar::class);
    }

    public function companyFeatureOverrides(): HasMany
    {
        return $this->hasMany(CompanyFeatureOverride::class);
    }

    public function currentPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'current_plan_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Project::class);
    }

    public function assignments(): HasManyThrough
    {
        return $this->hasManyThrough(Assignment::class, Project::class);
    }

    public function timeEntries(): HasManyThrough
    {
        return $this->hasManyThrough(TimeEntry::class, Project::class);
    }

    public function scopeHasUsers(Builder $query): Builder
    {
        return $query->whereHas('users');
    }

    public function scopeHasActiveSubscription(Builder $query): Builder
    {
        return $query->where('subscription_status', CompanySubscriptionStatusEnum::ACTIVE)
            ->where('subscription_period_start', '<=', now())
            ->where('subscription_period_end', '>=', now());
    }

    public function scopeIsInTrial(Builder $query): Builder
    {
        return $query
            ->where('subscription_status', CompanySubscriptionStatusEnum::TRIAL)
            ->where('subscription_trial_end', '<=', now());
    }

    public function scopeHasFeatures(Builder $query): Builder
    {
        return $query->whereHas('companyFeatureOverrides')
            ->orWhereHas('currentPlan.planFeatures');
    }

    /**
     * Check if the company has an active subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === CompanySubscriptionStatusEnum::ACTIVE
            && $this->subscription_period_start?->lte(now())
            && $this->subscription_period_end?->gte(now());
    }

    /**
     * Check if the company is in trial period
     */
    public function isInTrial(): bool
    {
        return $this->subscription_status === CompanySubscriptionStatusEnum::TRIAL
            && $this->subscription_trial_end?->gte(now());
    }

    /**
     * Check if company has access to a feature
     */
    public function hasFeature(string $featureKey): bool
    {
        // Check override first
        $override = $this->companyFeatureOverrides()
            ->whereHas('feature', fn($q) => $q->where('key', $featureKey))
            ->first();

        if ($override) {
            return $this->evaluateFeatureValue($override->feature->type, $override->value);
        }

        // Check plan feature if subscription is active
        if (!$this->hasActiveSubscription() && !$this->isInTrial()) {
            return false;
        }

        if (!$this->currentPlan) {
            return false;
        }

        $planFeature = $this->currentPlan->planFeatures()
            ->whereHas('feature', fn($q) => $q->where('key', $featureKey))
            ->first();

        return $planFeature
            && $this->evaluateFeatureValue($planFeature->feature->type, $planFeature->value);
    }

    /**
     * Get feature value (considering overrides)
     */
    public function getFeatureValue(string $featureKey): mixed
    {
        // Override takes precedence
        $override = $this->companyFeatureOverrides()
            ->whereHas('feature', fn($q) => $q->where('key', $featureKey))
            ->first();

        if ($override) {
            return $override->value;
        }

        // Fall back to plan
        if (!$this->hasActiveSubscription() && !$this->isInTrial()) {
            return null;
        }

        if (!$this->currentPlan) {
            return null;
        }

        $planFeature = $this->currentPlan->planFeatures()
            ->whereHas('feature', fn($q) => $q->where('key', $featureKey))
            ->first();

        return $planFeature?->value;
    }

    /**
     * Get the seats limit for the subscription
     */
    public function getSeatsLimit(): ?int
    {
        return $this->subscription_seats_limit;
    }

    /**
     * Check if company has reached the seats limit
     */
    public function hasReachedSeatsLimit(): bool
    {
        if (!$this->subscription_seats_limit) {
            return false; // No limit
        }

        $activeUsersCount = $this->users()->wherePivot('active', true)->count();
        return $activeUsersCount >= $this->subscription_seats_limit;
    }

    /**
     * Evaluate feature value based on type
     */
    private function evaluateFeatureValue(FeatureTypeEnum $type, mixed $value): bool
    {
        return match($type) {
            FeatureTypeEnum::BOOLEAN => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            FeatureTypeEnum::LIMIT => (int) $value > 0,
            FeatureTypeEnum::TIER => !empty($value),
        };
    }

}
