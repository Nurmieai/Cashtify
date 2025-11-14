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
        Schema::create('midtrans_webhooks', function (Blueprint $table) {
            $table->bigIncrements('mwh_id');

            // Reference order_id and (optional) transaction in our DB
            $table->string('mwh_order_id')->nullable()->index();
            $table->unsignedBigInteger('mwh_tst_id')->nullable()->index();

            // Meta
            $table->string('mwh_event')->nullable(); // contoh: settlement, pending, capture, expire
            $table->boolean('mwh_verified')->default(false);

            // Raw data
            $table->json('mwh_headers')->nullable();
            $table->json('mwh_payload')->nullable();

            // Audit timestamps
            $table->timestamps();

            // Foreign key to transactions (optional)
            $table->foreign('mwh_tst_id')->references('tst_id')->on('transactions')->onDelete('set null');

            // Rename timestamps supaya konsisten
            $table->renameColumn('created_at', 'mwh_created_at');
            $table->renameColumn('updated_at', 'mwh_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midtrans_webhooks');
    }
};
