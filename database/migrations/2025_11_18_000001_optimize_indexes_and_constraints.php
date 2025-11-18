<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->index(['company_id', 'status'], 'projects_company_status_idx');
            $table->index(['company_id', 'type'], 'projects_company_type_idx');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->index(['project_id', 'status'], 'tasks_project_status_idx');
            $table->index('assignee_company_user_id', 'tasks_assignee_idx');
            $table->index('created_by_company_user_id', 'tasks_creator_idx');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->index('project_id', 'assignments_project_idx');
            $table->index('company_user_id', 'assignments_company_user_idx');
        });

        Schema::table('time_entries', function (Blueprint $table) {
            $table->index('task_id', 'time_entries_task_idx');
        });

        Schema::table('company_subscriptions', function (Blueprint $table) {
            $table->index(['company_id', 'period_start'], 'company_subscriptions_company_period_idx');
            $table->index(['company_id', 'status'], 'company_subscriptions_company_status_idx');
            $table->index('plan_id', 'company_subscriptions_plan_idx');
        });

        Schema::table('plan_features', function (Blueprint $table) {
            $table->unique(['plan_id', 'feature_id'], 'plan_features_unique_plan_feature');
            $table->index('plan_id', 'plan_features_plan_idx');
            $table->index('feature_id', 'plan_features_feature_idx');
        });

        Schema::table('company_feature_overrides', function (Blueprint $table) {
            $table->index('feature_id', 'company_feature_overrides_feature_idx');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_company_status_idx');
            $table->dropIndex('projects_company_type_idx');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_project_status_idx');
            $table->dropIndex('tasks_assignee_idx');
            $table->dropIndex('tasks_creator_idx');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropIndex('assignments_project_idx');
            $table->dropIndex('assignments_company_user_idx');
        });

        Schema::table('time_entries', function (Blueprint $table) {
            $table->dropIndex('time_entries_task_idx');
        });

        Schema::table('company_subscriptions', function (Blueprint $table) {
            $table->dropIndex('company_subscriptions_company_period_idx');
            $table->dropIndex('company_subscriptions_company_status_idx');
            $table->dropIndex('company_subscriptions_plan_idx');
        });

        Schema::table('plan_features', function (Blueprint $table) {
            $table->dropUnique('plan_features_unique_plan_feature');
            $table->dropIndex('plan_features_plan_idx');
            $table->dropIndex('plan_features_feature_idx');
        });

        Schema::table('company_feature_overrides', function (Blueprint $table) {
            $table->dropIndex('company_feature_overrides_feature_idx');
        });
    }
};

