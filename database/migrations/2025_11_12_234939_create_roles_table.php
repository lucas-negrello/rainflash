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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            $table->string('key');
            $table->smallInteger('scope');

            $table->string('name');
            $table->text('description')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->unique(['key', 'scope']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
