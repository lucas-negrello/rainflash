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
        Schema::create('company_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');

            $table->text('primary_title')->nullable();
            $table->string('currency', 3)->nullable();
            $table->boolean('active')->default(true);
            $table->timestampTz('joined_at')->nullable();
            $table->timestampTz('left_at')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['company_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_users');
    }
};
