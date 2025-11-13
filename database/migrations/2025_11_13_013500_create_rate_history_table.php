<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_user_id');

            $table->dateTimeTz('effective_from');
            $table->dateTimeTz('effective_to')->nullable();
            $table->decimal('hour_cost', 12, 2);
            $table->string('currency', 3);
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['company_user_id', 'effective_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_history');
    }
};

