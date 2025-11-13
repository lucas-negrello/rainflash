<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_webhooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');

            $table->text('url');
            $table->text('secret');
            $table->boolean('active')->default(true);
            $table->jsonb('event_filters')->nullable();
            $table->jsonb('retry_policy')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_webhooks');
    }
};
