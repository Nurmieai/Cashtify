<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('crs_item_id');

            $table->unsignedBigInteger('crs_item_cart_id');
            $table->unsignedBigInteger('crs_item_product_id')->nullable();

            $table->integer('crs_item_quantity')->default(1);
            $table->bigInteger('crs_item_price')->default(0);
            $table->bigInteger('crs_item_subtotal')->default(0);

            // audit fields
            $table->unsignedBigInteger('crs_item_created_by')->nullable();
            $table->unsignedBigInteger('crs_item_updated_by')->nullable();
            $table->unsignedBigInteger('crs_item_deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->string('crs_item_sys_note')->nullable();

            // Foreign keys
            $table->foreign('crs_item_cart_id')
                ->references('crs_id')->on('carts')
                ->onDelete('cascade');

            $table->foreign('crs_item_product_id')
                ->references('prd_id')->on('products')
                ->onDelete('set null');

            $table->foreign('crs_item_created_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('crs_item_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
            $table->foreign('crs_item_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');

            // Rename timestamps
            $table->renameColumn('created_at', 'crs_item_created_at');
            $table->renameColumn('updated_at', 'crs_item_updated_at');
            $table->renameColumn('deleted_at', 'crs_item_deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
