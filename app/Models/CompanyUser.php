<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @use HasFactory<\Database\Factories\CompanyUserFactory> */
class CompanyUser extends Model
{
    use HasFactory;

    protected $table = 'company_user';

    protected $fillable = [
        'company_id',
        'user_id',
        'primary_title',
        'currency',
        'active',
        'joined_at',
        'left_at',
        'meta',
    ];

    protected $casts = [
        'active' => 'boolean',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'meta' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_user_roles', 'company_user_id', 'role_id');
    }

    public function workSchedules(): HasMany
    {
        return $this->hasMany(WorkSchedule::class);
    }

    public function rateHistory(): HasMany
    {
        return $this->hasMany(RateHistory::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'actor_company_user_id');
    }

    public function reportJobs(): HasMany
    {
        return $this->hasMany(ReportJob::class, 'requested_by_company_user_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members', 'company_user_id', 'team_id')
            ->withPivot('role_in_team', 'joined_at', 'left_at', 'meta')
            ->withTimestamps();
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'company_user_id');
    }

    public function ptoRequestsAsRequirer(): HasMany
    {
        return $this->hasMany(PtoRequest::class, 'company_user_id');
    }

    public function ptoRequestsAsApprover(): HasMany
    {
        return $this->hasMany(PtoRequest::class, 'approved_by_company_user_id');
    }

    public function ptoApprovals(): HasMany
    {
        return $this->hasMany(PtoApproval::class, 'approver_company_user_id');
    }
}
