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
        Schema::create('cash_advances', function (Blueprint $table) {
            $table->id();
            $table->string('special_disbursing_officer');
            $table->string('position');
            $table->string('station');
            $table->decimal('cash_advance_amount', 10 ,2);
            $table->string('cash_advance_date');
            $table->string('dv_number');
            $table->string('ors_burs_number');
            $table->string('responsibility_code');
            $table->string('uacs_code');
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
        Schema::dropIfExists('cash_advances');
    }
};
