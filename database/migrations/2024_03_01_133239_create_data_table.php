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
        Schema::create('data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicleId')->unsigned();
            $table->integer('inhouseSellerId')->unsigned();
            $table->integer('buyerId')->unsigned();
            $table->integer('modelId')->unsigned();
            $table->dateTime('saleDate');
            $table->dateTime('buyDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
