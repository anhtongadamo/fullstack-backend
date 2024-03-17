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
        Schema::create('tour_invoice', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->double('amount');
            $table->tinyInteger('status')->comment('1: Unpaid, 2: Paid, 3: Cancelled')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_invoice');
    }
};
