<?php

namespace App\Console\Commands;

use App\Models\Seller;
use App\Models\SellerSubscription;
use Illuminate\Console\Command;

class CreateMissingSubscriptions extends Command
{
    protected $signature = 'subscriptions:create-missing';
    protected $description = 'Create subscriptions for sellers who don\'t have one';

    public function handle()
    {
        $this->info('Checking for sellers without subscriptions...');

        $sellers = Seller::doesntHave('sellerSubscriptions')->get();

        if ($sellers->isEmpty()) {
            $this->info('All sellers have subscriptions!');
            return Command::SUCCESS;
        }

        $this->info("Found {$sellers->count()} sellers without subscriptions");

        foreach ($sellers as $seller) {
            SellerSubscription::create([
                'seller_id' => $seller->id,
                'subscription_type' => 'monthly',
                'amount' => $seller->monthly_rent ?? 500.00,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'status' => 'expired',
                'auto_renew' => true,
            ]);

            $this->line("✓ Created subscription for: {$seller->name} (ID: {$seller->id})");
        }

        $this->info("\nSuccessfully created {$sellers->count()} subscriptions!");

        return Command::SUCCESS;
    }
}
