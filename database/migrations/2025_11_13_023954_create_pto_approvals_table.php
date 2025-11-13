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
        Schema::create('pto_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pto_request_id');
            $table->unsignedBigInteger('approver_company_user_id');

            $table->smallInteger('decision');
            $table->timestampTz('decided_at');
            $table->text('note')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('pto_request_id')
                ->references('id')
                ->on('pto_requests')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('approver_company_user_id')
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
        Schema::dropIfExists('pto_approvals');
    }
};
