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
        if (!Schema::hasColumn('products', 'special_comments')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('special_comments')->nullable()->after('origin');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'special_comments')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('special_comments');
            });
        }
    }
};
