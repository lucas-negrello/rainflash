<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @use HasFactory<\Database\Factories\RateHistoryFactory> */
class RateHistory extends Model
{
    use HasFactory;

    protected $table = 'rate_history';

    protected $fillable = [
        'company_user_id',
        'effective_from',
        'effective_to',
        'hour_cost',
        'currency',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
    ];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class);
    }
}
