<?php

namespace App\Http\Controllers;

use App\Models\GigOrder;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process($orderId)
    {
        $order = GigOrder::findOrFail($orderId);

        // Security check
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $gateway = PaymentGateway::where('name', $order->payment_method)->first();

        if (!$gateway || !$gateway->is_active) {
            return redirect()->route('customer.dashboard')->with('error', 'Payment gateway not available.');
        }

        // Check if we are in Mock/Sandbox mode
        if ($gateway->mode === 'sandbox') {
            return redirect()->route('payment.mock', ['gateway' => $gateway->name, 'order_id' => $order->id]);
        }

        // REAL INTEGRATION LOGIC WOULD GO HERE
        // e.g. return $this->processStripe($order, $gateway);
        
        return redirect()->route('customer.dashboard')->with('error', 'Live payment not implemented yet.');
    }

    public function mockPage($gatewayName, $orderId)
    {
        $order = GigOrder::findOrFail($orderId);
        $gateway = PaymentGateway::where('name', $gatewayName)->firstOrFail();

        return view('payment.mock_gateway', compact('order', 'gateway'));
    }

    public function mockSuccess($orderId)
    {
        $order = GigOrder::findOrFail($orderId);
        
        $order->update([
            'payment_status' => 'paid',
            'status' => 'accepted' // Auto accept paid orders or keep pending
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Payment successful! Order #' . $order->id . ' confirmed.');
    }
}
