<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('shp_id');
            $table->unsignedBigInteger('shp_transaction_id');
            $table->enum('shp_status', [
                'pending',
                'packed',
                'sending',
                'delivered',
                'returned'
            ])->default('pending');

            $table->string('shp_courier')->nullable();
            $table->string('shp_service')->nullable();
            $table->string('shp_tracking_code')->nullable();

            $table->dateTime('shp_sent_at')->nullable();
            $table->dateTime('shp_delivered_at')->nullable();
            $table->text('shp_notes')->nullable();

            // audit
            $table->unsignedBigInteger('shp_created_by')->nullable();
            $table->unsignedBigInteger('shp_updated_by')->nullable();
            $table->unsignedBigInteger('shp_deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->string('shp_sys_note')->nullable();

            // foreign keys
            $table->foreign('shp_transaction_id')->references('tst_id')->on('transactions')->onDelete('cascade');
            $table->foreign('shp_created_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('shp_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('shp_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');

            // rename timestamps
            $table->renameColumn('created_at', 'shp_created_at');
            $table->renameColumn('updated_at', 'shp_updated_at');
            $table->renameColumn('deleted_at', 'shp_deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
