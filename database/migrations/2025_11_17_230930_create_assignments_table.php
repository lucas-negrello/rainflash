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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('company_user_id');
            $table->dateTimeTz('effective_from');

            $table->dateTimeTz('effective_to')->nullable();
            $table->float('weekly_capacity_hours');
            $table->float('hour_rate_override')->nullable();
            $table->float('price_rate_override')->nullable();

            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['project_id', 'company_user_id', 'effective_from']);

            $table->index('project_id', 'assignments_project_idx');
            $table->index('company_user_id', 'assignments_company_user_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
