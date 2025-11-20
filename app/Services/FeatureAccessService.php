<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Feature;

class FeatureAccessService
{
    /**
     * Check if a company can access a specific feature
     */
    public function canAccess(Company $company, string $featureKey): bool
    {
        return $company->hasFeature($featureKey);
    }

    /**
     * Get the value of a feature for a company
     */
    public function getValue(Company $company, string $featureKey): mixed
    {
        return $company->getFeatureValue($featureKey);
    }

    /**
     * Get feature limit value (for LIMIT type features)
     */
    public function getLimit(Company $company, string $featureKey): ?int
    {
        $value = $this->getValue($company, $featureKey);
        return $value ? (int) $value : null;
    }

    /**
     * Get feature tier value (for TIER type features)
     */
    public function getTier(Company $company, string $featureKey): ?string
    {
        $value = $this->getValue($company, $featureKey);
        return $value ? (string) $value : null;
    }

    /**
     * Check if feature is enabled (for BOOLEAN type features)
     */
    public function isEnabled(Company $company, string $featureKey): bool
    {
        $value = $this->getValue($company, $featureKey);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set or update a feature override for a company
     */
    public function setOverride(Company $company, string $featureKey, mixed $value, ?array $meta = null): void
    {
        $feature = Feature::where('key', $featureKey)->firstOrFail();

        $override = $company->companyFeatureOverrides()
            ->where('feature_id', $feature->id)
            ->first();

        if ($override) {
            $data = ['value' => $value];
            if ($meta !== null) {
                $data['meta'] = $meta;
            }
            $override->update($data);
        } else {
            $data = [
                'company_id' => $company->id,
                'feature_id' => $feature->id,
                'value' => $value,
            ];
            if ($meta !== null) {
                $data['meta'] = $meta;
            }
            $company->companyFeatureOverrides()->create($data);
        }
    }

    /**
     * Remove a feature override for a company
     */
    public function removeOverride(Company $company, string $featureKey): void
    {
        $company->companyFeatureOverrides()
            ->whereHas('feature', fn($q) => $q->where('key', $featureKey))
            ->delete();
    }

    /**
     * Check if company has an override for a feature
     */
    public function hasOverride(Company $company, string $featureKey): bool
    {
        return $company->companyFeatureOverrides()
            ->whereHas('feature', fn($q) => $q->where('key', $featureKey))
            ->exists();
    }

    /**
     * Get all features available to a company (plan + overrides)
     */
    public function getAllFeatures(Company $company): array
    {
        $features = [];

        // Get plan features
        if ($company->currentPlan) {
            foreach ($company->currentPlan->planFeatures as $planFeature) {
                $features[$planFeature->feature->key] = [
                    'name' => $planFeature->feature->name,
                    'type' => $planFeature->feature->type,
                    'value' => $planFeature->value,
                    'source' => 'plan',
                ];
            }
        }

        // Override with company-specific overrides
        foreach ($company->companyFeatureOverrides as $override) {
            $features[$override->feature->key] = [
                'name' => $override->feature->name,
                'type' => $override->feature->type,
                'value' => $override->value,
                'source' => 'override',
            ];
        }

        return $features;
    }

    /**
     * Check if company can add more of a resource based on limit
     */
    public function canAddMore(Company $company, string $featureKey, int $currentCount): bool
    {
        $limit = $this->getLimit($company, $featureKey);

        if ($limit === null) {
            return true; // No limit
        }

        return $currentCount < $limit;
    }
}

