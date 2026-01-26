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
        Schema::create('downtimes_reasons_production_orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_time_downtime');
            $table->dateTime('end_time_downtime');
            $table->integer('duration_downtime');
            $table->foreignId('downtime_reason_id')->constrained('downtimes_reasons')->onDelete('cascade');
            $table->foreignId('production_order_id')->constrained('production_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downtimes_reasons_production_orders');
    }
};
