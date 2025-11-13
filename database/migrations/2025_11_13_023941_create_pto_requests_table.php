<?php

use App\Enums\PtoRequestStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pto_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('company_user_id');
            $table->unsignedBigInteger('approved_by_company_user_id')->nullable();

            $table->smallInteger('status')->default(PtoRequestStatusEnum::REQUESTED);
            $table->smallInteger('type');
            $table->smallInteger('hours_option');

            $table->dateTimeTz('start_date');
            $table->dateTimeTz('end_date');

            $table->decimal('hours_amount', 5, 2)->nullable();
            $table->timestampTz('requested_at');
            $table->timestampTz('approved_at')->nullable();
            $table->text('reason')->nullable();
            $table->jsonb('meta')->nullable();

            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('approved_by_company_user_id')
                ->references('id')
                ->on('company_user')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pto_requests');
    }
};
