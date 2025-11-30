<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {

            $table->bigIncrements('tst_id');

            $table->string('tst_invoice')->unique();
            $table->unsignedBigInteger('tst_buyer_id');
            $table->unsignedBigInteger('tst_seller_id');

            $table->integer('tst_total');
            $table->integer('tst_subtotal');
            $table->integer('tst_shipping_cost')->default(0);

            $table->string('tst_payment_method');
            $table->double('tst_payment_amount')->default(0);
            $table->timestamp('tst_payment_paid_at')->nullable();
            $table->timestamp('tst_expires_at')->nullable();

            $table->enum('tst_payment_status', [
                'pending',
                'paid',
                'failed',
                'cancelled',
            ])->default('pending');

            $table->enum('tst_status', [
                'pending',
                'paid',
                'verified',
                'sent',
                'done',
                'cancelled',
                'waiting',
            ])->default('pending');

            $table->string('tst_shipping_service')->nullable();
            $table->string('tst_shipping_courier')->nullable();
            $table->string('tst_tracking_code')->nullable();
            $table->string('tst_qr_reference')->nullable();

            $table->text('tst_notes')->nullable();
            $table->string('tst_sys_note')->nullable();

            $table->timestamp('tst_created_at')->nullable();
            $table->timestamp('tst_updated_at')->nullable();
            $table->timestamp('tst_deleted_at')->nullable();

            $table->unsignedBigInteger('tst_created_by')->nullable();
            $table->unsignedBigInteger('tst_updated_by')->nullable();
            $table->unsignedBigInteger('tst_deleted_by')->nullable();

            $table->foreign('tst_buyer_id')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('tst_seller_id')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('tst_created_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('tst_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('tst_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
