<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('type', ['increase', 'decrease']);
            $table->decimal('value', 9, 3, true);
            $table->boolean('status');
            $table->text('description');
            $table->string('code')->unique();
            $table->decimal('fee',9,3,1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
