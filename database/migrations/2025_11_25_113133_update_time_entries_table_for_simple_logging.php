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
        Schema::table('time_entries', function (Blueprint $table) {
            // Adiciona o campo date para apontamentos simples
            $table->date('date')->nullable()->after('reviewed_by_company_user_id');

            // Torna started_at e ended_at opcionais
            $table->timestampTz('started_at')->nullable()->change();
            $table->timestampTz('ended_at')->nullable()->change();

            // Adiciona índice no campo date
            $table->index('date', 'time_entries_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_entries', function (Blueprint $table) {
            $table->dropIndex('time_entries_date_idx');
            $table->dropColumn('date');

            // Reverte started_at e ended_at para não nulos
            $table->timestampTz('started_at')->nullable(false)->change();
            $table->timestampTz('ended_at')->nullable(false)->change();
        });
    }
};

