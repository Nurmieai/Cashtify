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
            $table->unsignedBigInteger('act_user_id')->unsigned();
            $table->string('act_exel_url');
            $table->date('act_period_from');
            $table->date('act_period_to');
            $table->integer('act_total_sales');
            $table->integer('act_total_items_sold');


            $table->integer('act_midtrans_total_transactions')->default(0);

            $table->integer('act_midtrans_total_amount')->default(0);

            $table->json('act_midtrans_transaction_ids')->nullable();


            $table->integer('act_total_shipments')->default(0);

            $table->integer('act_total_shipping_cost')->default(0);

            $table->json('act_shipments_json')->nullable();


            $table->integer('act_total_income')->default(0);
            $table->integer('act_total_expense')->default(0);


            $table->timestamps();
            $table->unsignedBigInteger('act_created_by')->unsigned()->nullable();
            $table->unsignedBigInteger('act_deleted_by')->unsigned()->nullable();
            $table->unsignedBigInteger('act_updated_by')->unsigned()->nullable();
            $table->softDeletes();
            $table->string('act_sys_note')->nullable();


            $table->foreign('act_user_id')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('act_created_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('act_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('act_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');

            $table->renameColumn('created_at', 'act_created_at');
            $table->renameColumn('updated_at', 'act_updated_at');
            $table->renameColumn('deleted_at', 'act_deleted_at');
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
