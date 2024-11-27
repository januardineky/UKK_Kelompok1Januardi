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
        Schema::create('competency_elements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('criteria');
            $table->unsignedBigInteger('competency_id');
            $table->foreign('competency_id')->references('id')->on('competency_standards')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_elements');
    }
};
