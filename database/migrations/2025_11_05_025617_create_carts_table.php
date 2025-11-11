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
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('crs_id');
            $table->dateTime('crs_time');
            $table->unsignedBigInteger('crs_user_id')->nullable();
            $table->unsignedBigInteger('crs_product_id')->nullable();

            $table->unsignedBigInteger('crs_created_by')->unsigned()->nullable();
            $table->unsignedBigInteger('crs_deleted_by')->unsigned()->nullable();
            $table->unsignedBigInteger('crs_updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('crs_sys_note')->nullable();

            $table->foreign('crs_user_id')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('crs_product_id')->references('prd_id')->on('products')->onDelete('cascade');

            $table->foreign('crs_created_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('crs_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('crs_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');

            $table->renameColumn('created_at', 'crs_created_at');
            $table->renameColumn('updated_at', 'crs_updated_at');
            $table->renameColumn('deleted_at', 'crs_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
