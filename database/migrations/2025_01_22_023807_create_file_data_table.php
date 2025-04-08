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
        Schema::create('file_data', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('file_id'); 
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->string('control_number');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('extension_name')->nullable();
            $table->date('birthdate');
            $table->string('status');
            $table->dateTime('date_time_claimed');
            $table->string('remarks')->nullable();
            $table->integer('amount');
            $table->string('assistance_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_data');
    }
};
