<?php
/**
 * Update products with NULL stock to 0
 * Run this once: php update_null_stock.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Updating products with NULL stock values...\n\n";

try {
    // Update products where stock is NULL to 0
    $updated = \Illuminate\Support\Facades\DB::table('products')
        ->whereNull('stock')
        ->update(['stock' => 0]);
    
    echo "✅ Updated {$updated} products with NULL stock to 0\n";
    
    // Check current stock values
    $products = \App\Models\Product::all();
    echo "\nCurrent product stock status:\n";
    echo str_repeat('-', 80) . "\n";
    printf("%-5s %-40s %-10s\n", "ID", "Name", "Stock");
    echo str_repeat('-', 80) . "\n";
    
    foreach ($products as $product) {
        $stockDisplay = $product->stock ?? 'NULL';
        printf("%-5d %-40s %-10s\n", 
            $product->id, 
            substr($product->name, 0, 40), 
            $stockDisplay
        );
    }
    
    echo str_repeat('-', 80) . "\n";
    echo "\n✅ Done! All products now have stock values.\n";
    echo "Products with stock = 0 will show 'Out of Stock' badge.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
