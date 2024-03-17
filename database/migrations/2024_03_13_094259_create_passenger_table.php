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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->string('given_name', 128)->nullable();
            $table->string('surname', 64)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('mobile', 16)->nullable();
            $table->string('passport', 16)->nullable();
            $table->date('birth_date')->nullable();
            $table->tinyInteger('status')->comment('1: Enabled, 2: Disabled')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
