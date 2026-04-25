<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'caret_range')) $table->string('caret_range')->nullable();
            if (!Schema::hasColumn('products', 'gold_price')) $table->decimal('gold_price', 12, 2)->nullable();
            if (!Schema::hasColumn('products', 'gold_cost_price')) $table->decimal('gold_cost_price', 12, 2)->nullable();
            if (!Schema::hasColumn('products', 'gem_cost_price')) $table->decimal('gem_cost_price', 12, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['caret_range', 'gold_price', 'gold_cost_price', 'gem_cost_price']);
        });
    }
};
