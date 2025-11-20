<?php

use App\Enums\TimeEntryStatusEnum;
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
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('created_by_company_user_id');
            $table->unsignedBigInteger('reviewed_by_company_user_id')->nullable();

            $table->timestampTz('started_at');
            $table->timestampTz('ended_at');
            $table->integer('duration_minutes');

            $table->smallInteger('origin');
            $table->text('notes')->nullable();
            $table->boolean('locked')->default(false);
            $table->smallInteger('status')->default(TimeEntryStatusEnum::PENDING);
            $table->timestampTz('approved_at')->nullable();

            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreign('created_by_company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('reviewed_by_company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->index('task_id', 'time_entries_task_idx');
            $table->index(['created_by_company_user_id', 'started_at']);
            $table->index(['project_id', 'started_at']);
            $table->index(['reviewed_by_company_user_id', 'approved_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
