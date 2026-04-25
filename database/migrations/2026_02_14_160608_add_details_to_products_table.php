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
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type')->nullable()->index(); // 'gem' or 'jewelry'
            $table->string('color')->nullable();
            $table->decimal('weight', 8, 3)->nullable(); // Carat weight
            $table->string('shape')->nullable(); // Shape / Cut
            $table->string('treatment')->nullable();
            $table->string('metal')->nullable();
            $table->decimal('original_price', 10, 2)->nullable(); // For discounts
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_type', 'color', 'weight', 'shape', 'treatment', 'metal', 'original_price']);
        });
    }
};
