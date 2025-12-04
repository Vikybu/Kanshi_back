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
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('theoritical_production_duration');
            $table->integer('theoritical_incoming_product_quantity');
            $table->integer('actual_incoming_product_quantity');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('theoritical_outgoing_product_quantity');
            $table->integer('actual_outgoing_product_quantity');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
