<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->bigIncrements('tst_item_id');
            $table->unsignedBigInteger('tst_item_transaction_id');
            $table->unsignedBigInteger('tst_item_product_id')->nullable();
            $table->string('tst_item_product_name');
            $table->integer('tst_item_quantity')->default(1);
            $table->bigInteger('tst_item_price')->default(0);
            $table->bigInteger('tst_item_subtotal')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // foreign keys (sesuaikan pk user/product jika beda)
            $table->foreign('tst_item_transaction_id')->references('tst_id')->on('transactions')->onDelete('cascade');
            $table->foreign('tst_item_product_id')->references('prd_id')->on('products')->onDelete('set null');

            // rename timestamps mengikuti pola migrations-mu
            $table->renameColumn('created_at', 'tst_item_created_at');
            $table->renameColumn('updated_at', 'tst_item_updated_at');
            $table->renameColumn('deleted_at', 'tst_item_deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
