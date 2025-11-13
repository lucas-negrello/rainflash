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
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('skill_id');

            $table->smallInteger('proficiency_level');
            $table->smallInteger('years_of_experience');
            $table->dateTimeTz('last_used_at');
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['user_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
