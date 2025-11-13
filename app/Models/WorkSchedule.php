<?php

namespace App\Models;

use App\Enums\WorkScheduleWeekdayEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @use HasFactory<\Database\Factories\WorkScheduleFactory> */
class WorkSchedule extends Model
{
    use HasFactory;

    protected $table = 'work_schedules';

    protected $fillable = [
        'company_user_id',
        'weekday',
        'effective_from',
        'effective_to',
        'daily_hours',
        'start_time',
        'end_time',
        'meta',
    ];

    protected $casts = [
        'weekday' => WorkScheduleWeekdayEnum::class,
        'meta' => 'array',
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
        // 'start_time' and 'end_time' are stored as time columns and kept as strings
    ];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class);
    }
}
