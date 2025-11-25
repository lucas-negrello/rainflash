<?php

use App\Models\Plan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!config('admin.features.enable_plans_seeder')) return;

        if (empty(config('admin.plans'))) return;

        $plans = config('admin.plans');

        foreach ($plans as $planData) {
            Plan::query()->firstOrCreate([
                'key' => $planData['key'],
            ], [
                'name' => $planData['name'],
                'price_monthly' => $planData['price_monthly'],
                'currency' => $planData['currency'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible.
    }
};
