<?php

namespace App\Models;

use App\Enums\FeatureTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeature extends Model
{
    use HasFactory;

    protected $table = 'plan_features';

    protected $fillable = [
        'plan_id',
        'feature_id',
        'value',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    protected static function booted()
    {
        static::saving(function ($planFeature) {
            $planFeature->validateAndNormalizeValue();
        });
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    /**
     * Validate and normalize value based on feature type
     */
    public function validateAndNormalizeValue(): void
    {
        if (!$this->feature) {
            return;
        }

        $type = $this->feature->type;

        $this->value = match($type) {
            FeatureTypeEnum::BOOLEAN => filter_var($this->value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            FeatureTypeEnum::LIMIT => (string) (int) $this->value,
            FeatureTypeEnum::TIER => (string) $this->value,
        };
    }

    /**
     * Get typed value based on feature type
     */
    public function getTypedValue(): mixed
    {
        if (!$this->feature) {
            return $this->value;
        }

        return match($this->feature->type) {
            FeatureTypeEnum::BOOLEAN => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            FeatureTypeEnum::LIMIT => (int) $this->value,
            FeatureTypeEnum::TIER => (string) $this->value,
        };
    }
}
