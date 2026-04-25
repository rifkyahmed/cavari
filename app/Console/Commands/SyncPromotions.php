<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-promotions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize active promotions with product prices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting promotion sync...');
        \App\Services\PromotionService::sync();
        $this->info('Promotions successfully synced and prices updated.');
    }
}
