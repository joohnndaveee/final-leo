<?php

namespace App\Console\Commands;

use App\Models\Seller;
use App\Models\SellerSubscription;
use Illuminate\Console\Command;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update expired seller subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired subscriptions...');

        // Find sellers with expired subscriptions
        $expiredSellers = Seller::where('subscription_end_date', '<', now()->toDateString())
            ->whereIn('subscription_status', ['active', 'inactive'])
            ->get();

        $count = 0;

        foreach ($expiredSellers as $seller) {
            // Update seller subscription status
            $seller->update([
                'subscription_status' => 'expired',
            ]);

            // Update the subscription record
            $subscription = $seller->sellerSubscriptions()->latest()->first();
            if ($subscription && $subscription->status === 'active') {
                $subscription->update([
                    'status' => 'expired',
                ]);
            }

            $this->line("Expired subscription for seller: {$seller->shop_name} (ID: {$seller->id})");
            $count++;

            // TODO: Send expiration notification email
            // Mail::to($seller->email)->queue(new SubscriptionExpired($seller));
        }

        $this->info("Total subscriptions expired: {$count}");

        return Command::SUCCESS;
    }
}
