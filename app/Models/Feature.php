<?php

namespace App\Models;

use App\Enums\FeatureTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    use HasFactory;

    protected $table = 'features';

    protected $fillable = [
        'key',
        'name',
        'type',
        'meta',
    ];

    protected $casts = [
        'type' => FeatureTypeEnum::class,
        'meta' => 'array',
    ];

    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function companyFeatureOverrides(): HasMany
    {
        return $this->hasMany(CompanyFeatureOverride::class);
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_features')
            ->withPivot(['value', 'meta'])
            ->withTimestamps();
    }

    public function scopeBoolean(Builder $query): Builder
    {
        return $query->where('type', FeatureTypeEnum::BOOLEAN);
    }

    public function scopeTier(Builder $query): Builder
    {
        return $query->where('type', FeatureTypeEnum::TIER);
    }

    public function scopeLimit(Builder $query): Builder
    {
        return $query->where('type', FeatureTypeEnum::LIMIT);
    }
}
