<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use App\Enums\TaskTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'type',
        'estimated_minutes',
        'assignee_company_user_id',
        'created_by_company_user_id',
        'meta',
    ];

    protected $casts = [
        'status' => TaskStatusEnum::class,
        'type' => TaskTypeEnum::class,
        'estimated_minutes' => 'integer',
        'meta' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function companyUserCreator(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'created_by_company_user_id');
    }

    public function companyUserAssignee(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'assignee_company_user_id');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }
}
