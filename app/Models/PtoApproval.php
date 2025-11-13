<?php

namespace App\Models;

use App\Enums\PtoApprovalDecisionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @use HasFactory<\Database\Factories\PtoApprovalFactory> */
class PtoApproval extends Model
{
    use HasFactory;

    protected $table = 'pto_approvals';

    protected $fillable = [
        'pto_request_id',
        'approver_company_user_id',
        'decision',
        'decided_at',
        'note',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'decision' => PtoApprovalDecisionEnum::class,
        'decided_at' => 'datetime',
    ];

    public function ptoRequest(): BelongsTo
    {
        return $this->belongsTo(PtoRequest::class);
    }

    public function approverCompanyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'approver_company_user_id');
    }
}
