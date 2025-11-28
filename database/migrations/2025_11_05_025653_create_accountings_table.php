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
        Schema::create('accountings', function (Blueprint $table) {
            $table->bigIncrements('act_id');

            $table->unsignedBigInteger('act_user_id');
            $table->string('act_excel_url');

            $table->date('act_period_from');
            $table->date('act_period_to');

            $table->integer('act_total_transactions')->default(0);
            $table->json('act_transaction_ids')->nullable();

            $table->integer('act_total_sales')->default(0);
            $table->integer('act_total_items_sold')->default(0);
            $table->integer('act_total_payment_amount')->default(0);
            $table->integer('act_total_shipping_cost')->default(0);

            $table->integer('act_total_income')->default(0);
            $table->integer('act_total_expense')->default(0);

            $table->timestamps();      // created_at & updated_at
            $table->softDeletes();     // deleted_at

            $table->unsignedBigInteger('act_created_by')->nullable();
            $table->unsignedBigInteger('act_deleted_by')->nullable();
            $table->unsignedBigInteger('act_updated_by')->nullable();
            $table->string('act_sys_note')->nullable();

            $table->foreign('act_user_id')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('act_created_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('act_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('act_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountings');
    }
};
