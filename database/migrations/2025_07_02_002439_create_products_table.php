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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('product_code')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->decimal('production_cost', 10, 2)->default(0.00);
            $table->decimal('selling_price', 10, 2)->default(0.00);
            $table->decimal('discount_price', 10, 2)->default(0.00)->nullable();
            $table->unsignedTinyInteger('discount_percentage')->default(0)->nullable();
            $table->unsignedSmallInteger('product_measurement')->nullable();
            $table->string('measurement_unit')->nullable();
            $table->boolean('track_inventory')->default(true);
            $table->unsignedSmallInteger('stock_count')->default(0)->nullable();
            $table->unsignedSmallInteger('safety_stock')->default(0)->nullable();
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('canonical_url')->nullable();
            $table->json('meta_tags')->nullable();
            $table->json('og_tags')->nullable();

            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
