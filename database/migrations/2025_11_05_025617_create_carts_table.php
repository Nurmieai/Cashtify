<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {

            $table->bigIncrements('crs_id');

            $table->unsignedBigInteger('crs_buyer_id')->nullable();

            $table->enum('crs_status', ['active', 'ordered'])->default('active');

            $table->bigInteger('crs_total_price')->default(0);
            $table->integer('crs_total_items')->default(0);

            // custom timestamps
            $table->timestamp('crs_created_at')->nullable();
            $table->timestamp('crs_updated_at')->nullable();
            $table->timestamp('crs_deleted_at')->nullable();

            $table->unsignedBigInteger('crs_created_by')->nullable();
            $table->unsignedBigInteger('crs_updated_by')->nullable();
            $table->unsignedBigInteger('crs_deleted_by')->nullable();

            $table->string('crs_sys_note')->nullable();

            // FK
            $table->foreign('crs_buyer_id')
                ->references('usr_id')->on('users')
                ->onDelete('cascade');

            $table->foreign('crs_created_by')
                ->references('usr_id')->on('users')
                ->onDelete('set null');

            $table->foreign('crs_updated_by')
                ->references('usr_id')->on('users')
                ->onDelete('set null');

            $table->foreign('crs_deleted_by')
                ->references('usr_id')->on('users')
                ->onDelete('set null');
        });

        Schema::create('cart_items', function (Blueprint $table) {

            $table->bigIncrements('crs_item_id');

            $table->unsignedBigInteger('crs_item_cart_id');
            $table->unsignedBigInteger('crs_item_product_id')->nullable();

            $table->integer('crs_item_quantity')->default(1);
            $table->bigInteger('crs_item_price')->default(0);
            $table->bigInteger('crs_item_subtotal')->default(0);

            $table->timestamp('crs_item_created_at')->nullable();
            $table->timestamp('crs_item_updated_at')->nullable();
            $table->timestamp('crs_item_deleted_at')->nullable();

            $table->unsignedBigInteger('crs_item_created_by')->nullable();
            $table->unsignedBigInteger('crs_item_updated_by')->nullable();
            $table->unsignedBigInteger('crs_item_deleted_by')->nullable();

            $table->string('crs_item_sys_note')->nullable();

            // FK
            $table->foreign('crs_item_cart_id')
                ->references('crs_id')->on('carts')
                ->onDelete('cascade');

            $table->foreign('crs_item_product_id')
                ->references('prd_id')->on('products')
                ->onDelete('set null');

            $table->foreign('crs_item_created_by')
                ->references('usr_id')->on('users')
                ->onDelete('set null');

            $table->foreign('crs_item_updated_by')
                ->references('usr_id')->on('users')
                ->onDelete('set null');

            $table->foreign('crs_item_deleted_by')
                ->references('usr_id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
