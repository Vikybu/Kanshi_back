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
            $table->string('production_order_reference');
            $table->integer('theoritical_raw_material_quantity');
            $table->integer('actual_raw_material_quantity');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('time_measurement');
            $table->integer('theoritical_final_product_quantity');
            $table->integer('actual_final_product_quantity');
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
