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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('pmt_id');
            $table->unsignedBigInteger('pmt_tst_id')->index();
            $table->enum('pmt_method', ['cash','midtrans','other'])->default('midtrans');
            $table->integer('pmt_amount')->default(0);
            $table->enum('pmt_status', ['pending','success','failed','expired','cancelled'])->default('pending');
            $table->string('pmt_midtrans_order_id')->nullable();
            $table->string('pmt_midtrans_transaction_id')->nullable();
            $table->string('pmt_payment_type')->nullable();
            $table->string('pmt_payment_code')->nullable();
            $table->string('pmt_fraud_status')->nullable();
            $table->string('pmt_dummy_account')->nullable();
            $table->enum('pmt_dummy_provider', ['dana','bca'])->nullable();
            $table->bigInteger('pmt_gross_amount')->nullable();
            $table->json('pmt_raw_response')->nullable();
            $table->timestamp('pmt_paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('pmt_created_by')->unsigned()->nullable();
            $table->unsignedBigInteger('pmt_updated_by')->unsigned()->nullable();
            $table->unsignedBigInteger('pmt_deleted_by')->unsigned()->nullable();

            // Foreign keys (sesuaikan jika users primary key berbeda)
            $table->foreign('pmt_tst_id')->references('tst_id')->on('transactions')->onDelete('cascade');
            $table->foreign('pmt_created_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('pmt_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('pmt_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');

            // Rename timestamp columns sesuai konvensi project
            $table->renameColumn('created_at', 'pmt_created_at');
            $table->renameColumn('updated_at', 'pmt_updated_at');
            $table->renameColumn('deleted_at', 'pmt_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
