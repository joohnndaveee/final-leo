<?php

namespace App\Console\Commands;

use App\Models\Seller;
use App\Models\SellerChat;
use Illuminate\Console\Command;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired';
    protected $description = 'Check and update expired seller subscriptions';

    public function handle()
    {
        $this->info('Checking for expired subscriptions...');

        $expiredSellers = Seller::where('subscription_end_date', '<', now()->toDateString())
            ->whereIn('subscription_status', ['active', 'inactive'])
            ->get();

        $count = 0;

        foreach ($expiredSellers as $seller) {
            $seller->update(['subscription_status' => 'expired']);

            $subscription = $seller->sellerSubscriptions()->latest()->first();
            if ($subscription && $subscription->status === 'active') {
                $subscription->update(['status' => 'expired']);
            }

            $count++;
            $this->line("Expired subscription for seller: {$seller->shop_name} (ID: {$seller->id})");

            $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
            $rentAmount = number_format((float) ($seller->monthly_rent ?? 500.00), 2);
            $endDateStr = $seller->subscription_end_date
                ? \Carbon\Carbon::parse($seller->subscription_end_date)->format('M d, Y')
                : now()->format('M d, Y');

            SellerChat::create([
                'seller_id' => $seller->id,
                'message' => "Hello {$name}!\n\nYour subscription has expired.\nDue date: {$endDateStr}\nAmount: PHP {$rentAmount}\nAction: Subscription -> Pay Monthly Rent (GCash)",
                'sender_type' => 'admin',
                'is_read' => false,
            ]);
        }

        $this->info("Total subscriptions expired: {$count}");
        return Command::SUCCESS;
    }
}

