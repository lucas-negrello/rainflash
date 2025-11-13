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
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_user_id');

            $table->smallInteger('weekday');
            $table->dateTimeTz('effective_from');
            $table->dateTimeTz('effective_to')->nullable();
            $table->float('daily_hours', 2);
            $table->time('start_time');
            $table->time('end_time');
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['company_user_id', 'weekday', 'effective_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
