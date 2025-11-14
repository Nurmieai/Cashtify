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
        $table->string('prd_card_url')->nullable(); // path gambar
        $table->unsignedBigInteger('prd_created_by')->nullable();
        $table->unsignedBigInteger('prd_deleted_by')->nullable();
        $table->unsignedBigInteger('prd_updated_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
        $table->string('usr_sys_note')->nullable();

        $table->foreign('prd_created_by')->references('usr_id')->on('users')->onDelete('cascade');
        $table->foreign('prd_updated_by')->references('usr_id')->on('users')->onDelete('cascade');
        $table->foreign('prd_deleted_by')->references('usr_id')->on('users')->onDelete('cascade');

        $table->renameColumn('updated_at', 'usr_updated_at');
        $table->renameColumn('created_at', 'usr_created_at');
        $table->renameColumn('deleted_at', 'usr_deleted_at');
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
