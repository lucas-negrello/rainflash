<?php

namespace App\Models;

use App\Enums\ProjectBillingModelEnum;
use App\Enums\ProjectStatusEnum;
use App\Enums\ProjectTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'type',
        'billing_model',
        'status',
        'meta',
    ];

    protected $casts = [
        'type' => ProjectTypeEnum::class,
        'billing_model' => ProjectBillingModelEnum::class,
        'status' => ProjectStatusEnum::class,
        'meta' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function companyUsers(): BelongsToMany
    {
        return $this->belongsToMany(CompanyUser::class, 'assignments', 'project_id', 'company_user_id')
            ->withPivot('effective_from', 'effective_to', 'weekly_capacity_hours', 'hour_rate_override', 'price_rate_override', 'meta')
            ->withTimestamps();
    }
}
