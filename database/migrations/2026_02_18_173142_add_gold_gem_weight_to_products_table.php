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
            if (!Schema::hasColumn('products', 'gold_weight')) {
                $table->decimal('gold_weight', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'gem_weight')) {
                $table->decimal('gem_weight', 10, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['gold_weight', 'gem_weight']);
        });
    }
};
