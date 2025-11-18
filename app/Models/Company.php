<?php

namespace App\Models;

use App\Enums\CompanyStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    protected $casts = [
        'status' => CompanyStatusEnum::class,
        'meta' => 'array',
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

    public function companySubscriptions(): HasMany
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function companyFeatureOverrides(): HasMany
    {
        return $this->hasMany(CompanyFeatureOverride::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'company_feature_overrides')
            ->withPivot('value', 'meta')
            ->withTimestamps();
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'company_subscriptions')
            ->withPivot('status', 'seats_limit', 'period_start', 'period_end', 'trial_end', 'meta')
            ->withTimestamps();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function currentPlan(): ?Plan
    {
        return $this->plans()->orderByDesc('period_start')->first();
    }
}
