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
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('user_email')->nullable()->after('usage_limit');
            $table->boolean('is_birthday_offer')->default(false)->after('user_email');
            $table->boolean('is_popup_seen')->default(false)->after('is_birthday_offer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['user_email', 'is_birthday_offer', 'is_popup_seen']);
        });
    }
};
