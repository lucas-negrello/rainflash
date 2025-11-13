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
}
