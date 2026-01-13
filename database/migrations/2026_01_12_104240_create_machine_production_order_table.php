<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('machine_production_order', function (Blueprint $table) {
            $table->id();

            $table->foreignId('machine_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('production_order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['machine_id', 'production_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_production_order');
    }
};
