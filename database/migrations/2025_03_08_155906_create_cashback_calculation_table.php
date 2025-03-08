<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cashback_calculations', function (Blueprint $table) {
            $table->id();
            $table->integer('ristretto')->default(0);
            $table->integer('espresso')->default(0);
            $table->integer('lungo')->default(0);
            $table->integer('cashback')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashback_calculation');
    }
};
