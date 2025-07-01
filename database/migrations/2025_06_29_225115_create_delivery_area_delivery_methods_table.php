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
        Schema::create('delivery_area_delivery_methods', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('delivery_area_id')->constrained('delivery_areas')->cascadeOnDelete();
            $table->foreignId('delivery_method_id')->constrained('delivery_methods')->cascadeOnDelete();
            $table->decimal('custom_price', 10, 2)->nullable();

            $table->unique(['delivery_area_id', 'delivery_method_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_area_delivery_methods');
    }
};
