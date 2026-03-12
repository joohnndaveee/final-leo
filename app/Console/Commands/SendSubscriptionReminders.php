<?php

namespace App\Console\Commands;

use App\Models\Seller;
use App\Models\SellerChat;
use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'subscriptions:send-reminders';
    protected $description = 'Send payment reminders for subscriptions expiring within 7 days';

    public function handle()
    {
        $this->info('Sending subscription reminders...');

        $expiringDate = now()->addDays(7)->toDateString();
        $sellers = Seller::where('subscription_end_date', '<=', $expiringDate)
            ->where('subscription_end_date', '>=', now()->toDateString())
            ->where('subscription_status', 'active')
            ->where('payment_notification_sent', false)
            ->get();

        $count = 0;

        foreach ($sellers as $seller) {
            $daysLeft = now()->diffInDays($seller->subscription_end_date, false);
            $seller->update(['payment_notification_sent' => true]);
            $count++;

            $this->line("Reminder sent to: {$seller->shop_name} (Expires in {$daysLeft} days)");

            $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
            $rentAmount = number_format((float) ($seller->monthly_rent ?? 500.00), 2);
            $endDateStr = $seller->subscription_end_date
                ? \Carbon\Carbon::parse($seller->subscription_end_date)->format('M d, Y')
                : 'N/A';

            SellerChat::create([
                'seller_id' => $seller->id,
                'message' => "Hello {$name}!\n\nJust a reminder: your subscription will expire soon.\nExpiry: {$endDateStr}\nDays left: {$daysLeft}\nAmount: PHP {$rentAmount}\nAction: Subscription -> Pay Monthly Rent (GCash)",
                'sender_type' => 'admin',
                'is_read' => false,
            ]);
        }

        $this->info("Total reminders sent: {$count}");
        return Command::SUCCESS;
    }
}

