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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('tst_id');
            $table->string('tst_invoice')->unique();
            $table->unsignedBigInteger('tst_buyer_id')->unsigned();
            $table->unsignedBigInteger('tst_seller_id')->unsigned();
            $table->integer('tst_total');
            $table->integer('tst_subtotal');
            $table->double('tst_payment_amount')->default(0);
            $table->timestamp('tst_payment_paid_at')->nullable();
            $table->integer('tst_shipping_cost')->default(0);
            $table->string('tst_payment_method');
            $table->timestamp('tst_expires_at')->nullable()->after('tst_payment_method');
            $table->enum('tst_payment_status', ['pending','paid'])->default('pending');
            $table->enum('tst_status', ['pending', 'paid', 'verified', 'sent', 'done', 'cancelled','waiting',
            ])->default('pending');
            $table->string('tst_shipping_service')->nullable();
            $table->string('tst_shipping_courier')->nullable();
            $table->string('tst_tracking_code')->nullable();
            $table->string('tst_qr_reference')->nullable();
            $table->text('tst_notes')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('tst_created_by')->unsigned()->nullable();
            $table->unsignedBigInteger('tst_deleted_by')->unsigned()->nullable();
            $table->unsignedBigInteger('tst_updated_by')->unsigned()->nullable();
            $table->softDeletes();
            $table->string('tst_sys_note')->nullable();

            $table->foreign('tst_buyer_id')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('tst_seller_id')->references('usr_id')->on('users')->onDelete('cascade');

            $table->foreign('tst_created_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('tst_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('tst_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');

            $table->renameColumn('created_at', 'tst_created_at');
            $table->renameColumn('updated_at', 'tst_updated_at');
            $table->renameColumn('deleted_at', 'tst_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
