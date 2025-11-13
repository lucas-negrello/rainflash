<?php

namespace App\Models;

use App\Enums\ReportJobStatusEnum;
use App\Enums\ReportJobTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @use HasFactory<\Database\Factories\ReportJobFactory> */
class ReportJob extends Model
{
    use HasFactory;

    protected $table = 'report_jobs';

    protected $fillable = [
        'company_id',
        'requested_by_company_user_id',
        'type',
        'status',
        'parameters',
        'storage_key',
        'meta',
    ];

    protected $casts = [
        'type' => ReportJobTypeEnum::class,
        'status' => ReportJobStatusEnum::class,
        'parameters' => 'array',
        'meta' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function requestedByCompanyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'requested_by_company_user_id');
    }
}
