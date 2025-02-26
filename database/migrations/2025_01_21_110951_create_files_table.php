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
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cash_advance_id'); 
            $table->foreign('cash_advance_id')->references('id')->on('cash_advances')->onDelete('cascade');
            $table->string('file_name'); 
            $table->bigInteger('total_amount');
            $table->bigInteger('total_beneficiary');
            $table->enum('location', ['onsite', 'offsite'])->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
