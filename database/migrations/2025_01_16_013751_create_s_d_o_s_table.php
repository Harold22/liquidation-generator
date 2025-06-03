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
        Schema::create('s_d_o_s', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('extension_name')->nullable();
            $table->string('position');
            $table->string('designation')->nullable();
            $table->string('station');
            $table->enum('status', ['Active', 'Inactive']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_d_o_s');
    }
};
