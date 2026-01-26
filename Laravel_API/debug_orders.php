<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\GigOrder;
use App\Models\User;

echo "--- DEBUG ORDERS START ---\n";
try {
    $orders = GigOrder::all();
    if ($orders->isEmpty()) {
        echo "No orders found in database.\n";
    } else {
        foreach ($orders as $order) {
            echo "Order ID: {$order->id} | ProviderID: {$order->provider_id} | UserID: {$order->user_id} | Status: '{$order->status}'\n";
        }
    }
} catch (\Exception $e) {
    echo "Error fetching orders: " . $e->getMessage() . "\n";
}
echo "--- DEBUG ORDERS END ---\n";
