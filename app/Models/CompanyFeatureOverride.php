<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyFeatureOverride extends Model
{
    use HasFactory;

    protected $table = 'company_feature_overrides';

    protected $fillable = [
        'company_id',
        'feature_id',
        'value',
        'meta',
    ];

    protected $casts = [
        'value' => 'array',
        'meta' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }
}
