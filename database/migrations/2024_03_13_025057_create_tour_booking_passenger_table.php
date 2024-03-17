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
        Schema::create('tour_booking_passenger', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->integer('passenger_id');
            $table->text('special_request')->nullable();
            $table->unique(['booking_id', 'passenger_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_booking_passenger');
    }
};
