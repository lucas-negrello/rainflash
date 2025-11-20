<?php

use App\Enums\CompanyStatusEnum;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('current_plan_id')->nullable();

            $table->string('name');
            $table->string('slug')->unique();
            $table->smallInteger('status')->default(CompanyStatusEnum::TRIAL);

            $table->smallInteger('subscription_status')->nullable();
            $table->integer('subscription_seats_limit')->nullable()->after('subscription_status');
            $table->dateTimeTz('subscription_period_start')->nullable()->after('subscription_seats_limit');
            $table->dateTimeTz('subscription_period_end')->nullable()->after('subscription_period_start');
            $table->dateTimeTz('subscription_trial_end')->nullable()->after('subscription_period_end');

            $table->jsonb('subscription_meta')->nullable()->after('subscription_trial_end');
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('current_plan_id')
                ->references('id')
                ->on('plans')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
