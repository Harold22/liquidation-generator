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
        Schema::create('cash_advance_allocations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('cash_advance_id'); 
            $table->foreign('cash_advance_id')->references('id')->on('cash_advances')->onDelete('cascade'); 
            $table->ulid('office_id'); 
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->decimal('amount', 10 ,2);
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_advance_allocations');
    }
};
