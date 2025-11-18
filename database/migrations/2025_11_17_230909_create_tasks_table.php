<?php

use App\Enums\TaskStatusEnum;
use App\Enums\TaskTypeEnum;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');

            $table->string('title');
            $table->text('description')->nullable();
            $table->smallInteger('status')->default(TaskStatusEnum::OPEN);
            $table->smallInteger('type')->default(TaskTypeEnum::TECH);
            $table->integer('estimated_minutes')->nullable();

            $table->unsignedBigInteger('assignee_company_user_id')->nullable();
            $table->unsignedBigInteger('created_by_company_user_id');

            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('assignee_company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('created_by_company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
