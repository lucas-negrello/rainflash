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
        Schema::create('resource_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->smallInteger('seniority');
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->jsonb('attachments')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_profiles');
    }
};
