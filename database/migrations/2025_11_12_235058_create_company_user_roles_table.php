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
        Schema::create('company_user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('company_user_id');
            $table->unsignedBigInteger('role_id');

            $table->primary(['company_user_id', 'role_id']);

            $table->foreign('company_user_id')
                ->references('id')->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_user_roles');
    }
};
