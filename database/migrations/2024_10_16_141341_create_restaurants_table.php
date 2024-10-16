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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->default('');
            $table->text('description');
            $table->integer('lowest_price')->unsigned();
            $table->integer('highest_price')->unsigned();
            $table->string('postal_code');
            $table->string('address');
            $table->time('opening_time');
            $table->time('closing_time');
            $table->integer('seating_capacity')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
