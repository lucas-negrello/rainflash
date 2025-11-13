<?php

use App\Enums\WebhookDeliveryStatusEnum;
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
        Schema::create('webhook_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('webhook_id');

            $table->string('event_key');
            $table->jsonb('payload_snipped')->nullable();
            $table->smallInteger('status')->default(WebhookDeliveryStatusEnum::PENDING);
            $table->integer('attempt_count')->default(0);
            $table->text('last_error')->nullable();
            $table->timestampTz('delivered_at')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('webhook_id')
                ->references('id')
                ->on('company_webhooks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_deliveries');
    }
};
