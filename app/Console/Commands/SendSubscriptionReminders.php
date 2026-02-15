<?php

namespace App\Console\Commands;

use App\Models\Seller;
use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders for subscriptions expiring within 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending subscription reminders...');

        // Find sellers with subscriptions expiring in 7 days or less
        $expiringDate = now()->addDays(7)->toDateString();
        
        $sellers = Seller::where('subscription_end_date', '<=', $expiringDate)
            ->where('subscription_end_date', '>=', now()->toDateString())
            ->where('subscription_status', 'active')
            ->where('payment_notification_sent', false)
            ->get();

        $count = 0;

        foreach ($sellers as $seller) {
            $daysLeft = now()->diffInDays($seller->subscription_end_date, false);
            
            // Update notification flag
            $seller->update([
                'payment_notification_sent' => true,
            ]);

            $this->line("Reminder sent to: {$seller->shop_name} (Expires in {$daysLeft} days)");
            $count++;

            // TODO: Send reminder email
            // Mail::to($seller->email)->queue(new SubscriptionExpiring($seller, $daysLeft));
        }

        $this->info("Total reminders sent: {$count}");

        return Command::SUCCESS;
    }
}
