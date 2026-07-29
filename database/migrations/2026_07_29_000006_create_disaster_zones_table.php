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
        Schema::create('disaster_zones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->geometry('polygon')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disaster_zones');
    }
};
