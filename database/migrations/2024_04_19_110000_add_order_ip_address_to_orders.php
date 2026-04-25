<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'order_ip_address')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('order_ip_address')->nullable()->after('payment_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'order_ip_address')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('order_ip_address');
            });
        }
    }
};
?>
