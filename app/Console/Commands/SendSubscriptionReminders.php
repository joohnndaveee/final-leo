<?php

namespace App\Console\Commands;

use App\Models\Seller;
use App\Models\SellerChat;
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

            $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
            $rentAmount = number_format((float) ($seller->monthly_rent ?? 500.00), 2);
            $endDateStr = $seller->subscription_end_date ? \Carbon\Carbon::parse($seller->subscription_end_date)->format('M d, Y') : 'N/A';

            SellerChat::create([
                'seller_id' => $seller->id,
                'message' => "Hello {$name}!\n\nJust a reminder: your subscription will expire soon.\nExpiry: {$endDateStr}\nDays left: {$daysLeft}\nAmount: ₱{$rentAmount}\nAction: Wallet → Pay Monthly Rent",
                'sender_type' => 'admin',
                'is_read' => false,
            ]);

            // TODO: Send reminder email
            // Mail::to($seller->email)->queue(new SubscriptionExpiring($seller, $daysLeft));
        }

        $this->info("Total reminders sent: {$count}");

        return Command::SUCCESS;
    }
}
