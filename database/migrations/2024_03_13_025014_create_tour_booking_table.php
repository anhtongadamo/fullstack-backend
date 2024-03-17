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
        Schema::create('tour_booking', function (Blueprint $table) {
            $table->id();
            $table->integer('tour_id');
            $table->date('tour_date');
            $table->tinyInteger('status')->nullable()->comment('1: Submitted, 2: Confirmed, 3: Cancelled');
            $table->index(['id', 'tour_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_booking');
    }
};
