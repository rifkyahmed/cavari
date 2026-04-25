<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecoverStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:recover-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recover stock from pending orders that were never completed (older than 24 hours)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting stock recovery for stale pending orders...');

        // Find orders that are 'pending' and haven't been paid, and are older than 24 hours
        $orders = Order::where('status', 'pending')
            ->where('payment_status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->with('items')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No stale pending orders found.');
            return;
        }

        foreach ($orders as $order) {
            DB::beginTransaction();
            try {
                $this->comment("Processing Order #{$order->id}...");

                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                        $this->line(" - Restored {$item->quantity}x {$product->name}");
                    }
                }

                // Update order status to 'expired' to prevent duplicate recovery
                $order->update([
                    'status' => 'expired',
                    'payment_status' => 'failed'
                ]);

                DB::commit();
                $this->info("Order #{$order->id} successfully expired and stock recovered.");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to recover stock for Order #{$order->id}: " . $e->getMessage());
            }
        }

        $this->info('Stock recovery process completed.');
    }
}
