<?php

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
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('feature_id');

            $table->text('value');
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('feature_id')
                ->references('id')
                ->on('features')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->unique(['plan_id', 'feature_id'], 'plan_features_unique_plan_feature');
            $table->index('plan_id', 'plan_features_plan_idx');
            $table->index('feature_id', 'plan_features_feature_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
