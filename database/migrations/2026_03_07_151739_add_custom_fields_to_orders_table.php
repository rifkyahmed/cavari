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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_custom')->default(false);
            $table->text('custom_description')->nullable();
            $table->string('payment_link_uuid')->nullable()->unique();
            $table->string('billing_address')->nullable()->change();
            $table->string('shipping_address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_custom', 'custom_description', 'payment_link_uuid']);
            // If they weren't nullable before, you'd probably revert them, but it's fine.
        });
    }
};
