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
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('numerator_id')->constrained('assets')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('denominator_id')->constrained('assets')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('ratio',9,3,1);
            $table->decimal('fee',9,3,1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};
