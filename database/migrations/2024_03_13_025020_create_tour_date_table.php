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
        Schema::create('tour_date', function (Blueprint $table) {
            $table->id();
            $table->integer('tour_id');
            $table->date('date');
            $table->tinyInteger('status')->comment('1: Enabled, 2: Disabled')->default(1);
            $table->index(['id', 'tour_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_date');
    }
};
