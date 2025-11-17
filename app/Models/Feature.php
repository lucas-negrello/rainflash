<?php

namespace App\Models;

use App\Enums\FeatureTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_feature_overrides')
                    ->withPivot('value', 'meta')
                    ->withTimestamps();
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_features')
                    ->withPivot('value', 'meta')
                    ->withTimestamps();
    }
}
