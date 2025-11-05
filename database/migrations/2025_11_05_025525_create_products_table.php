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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('prd_id');
            $table->string('prd_name');
            $table->text('prd_description');
            $table->enum('prd_status', ['tersedia', 'tidak tersedia']);
            $table->integer('prd_price');
            $table->unsignedBigInteger('prd_created_by')->unsigned()->nullable();
            $table->unsignedBigInteger('prd_deleted_by')->unsigned()->nullable();
            $table->unsignedBigInteger('prd_updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
