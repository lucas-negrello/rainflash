<?php

namespace App\Models;

use App\Enums\PtoRequestHoursOptionEnum;
use App\Enums\PtoRequestStatusEnum;
use App\Enums\PtoRequestTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/** @use HasFactory<\Database\Factories\PtoRequestFactory> */
class PtoRequest extends Model
{
    use HasFactory;

    protected $table = 'pto_requests';

    protected $fillable = [
        'company_id',
        'company_user_id',
        'approved_by_company_user_id',
        'status',
        'type',
        'hours_option',
        'start_date',
        'end_date',
        'hours_amount',
        'requested_at',
        'approved_at',
        'reason',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'status' => PtoRequestStatusEnum::class,
        'type' => PtoRequestTypeEnum::class,
        'hours_option' => PtoRequestHoursOptionEnum::class,
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'approved_by_company_user_id');
    }

    public function ptoApproval(): HasOne
    {
        return $this->hasOne(PtoApproval::class);
    }
}
