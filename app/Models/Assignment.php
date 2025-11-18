<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';

    protected $fillable = [
        'project_id',
        'company_user_id',
        'effective_from',
        'effective_to',
        'weekly_capacity_hours',
        'hour_rate_override',
        'price_rate_override',
        'meta',
    ];

    protected $casts = [
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
        'weekly_capacity_hours' => 'float',
        'hour_rate_override' => 'float',
        'price_rate_override' => 'float',
        'meta' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class);
    }
}
