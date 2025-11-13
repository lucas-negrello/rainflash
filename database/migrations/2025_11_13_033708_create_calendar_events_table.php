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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calendar_id');

            $table->dateTimeTz('date');
            $table->smallInteger('type');
            $table->float('hours')->nullable();
            $table->text('note')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('calendar_id')
                ->references('id')
                ->on('calendars')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['calendar_id', 'date', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
