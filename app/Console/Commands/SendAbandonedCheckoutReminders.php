<?php

namespace App\Console\Commands;

use App\Models\AbandonedCheckout;
use App\Mail\AbandonedCheckoutReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAbandonedCheckoutReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkouts:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically send reminder emails for abandoned checkouts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for abandoned checkouts...');

        // Find checkouts that haven't received a reminder and were created at least 1 hour ago
        // Adjust the time threshold as needed (e.g., 24 hours)
        $abandonedCheckouts = AbandonedCheckout::whereNull('reminder_sent_at')
            ->where('updated_at', '<=', now()->subMinutes(5))
            ->get();

        if ($abandonedCheckouts->isEmpty()) {
            $this->info('No abandoned checkouts found.');
            return;
        }

        $this->info("Found {$abandonedCheckouts->count()} abandoned checkouts. Sending reminders...");

        foreach ($abandonedCheckouts as $checkout) {
            try {
                Mail::to($checkout->user_email)
                    ->send(new AbandonedCheckoutReminder($checkout));

                $checkout->update([
                    'reminder_sent_at' => now()
                ]);

                $this->line("Reminder sent to: {$checkout->user_email}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder to {$checkout->user_email}: " . $e->getMessage());
                Log::error("Abandoned checkout reminder failed for {$checkout->user_email}: " . $e->getMessage());
            }
        }

        $this->info('Process completed.');
    }
}
