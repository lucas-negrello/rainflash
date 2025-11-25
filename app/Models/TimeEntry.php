<?php

namespace App\Models;

use App\Enums\TimeEntryOriginEnum;
use App\Enums\TimeEntryStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    protected $table = 'time_entries';

    protected $fillable = [
        'project_id',
        'task_id',
        'created_by_company_user_id',
        'reviewed_by_company_user_id',
        'date',
        'started_at',
        'ended_at',
        'duration_minutes',
        'origin',
        'notes',
        'locked',
        'status',
        'approved_at',
        'meta',
    ];

    protected $casts = [
        'date' => 'date',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_minutes' => 'integer',
        'locked' => 'boolean',
        'origin' => TimeEntryOriginEnum::class,
        'status' => TimeEntryStatusEnum::class,
        'approved_at' => 'datetime',
        'meta' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function companyUserCreator(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'created_by_company_user_id');
    }

    public function companyUserReviewer(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'reviewed_by_company_user_id');
    }
}
