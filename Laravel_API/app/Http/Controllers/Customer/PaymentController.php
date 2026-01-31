<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\GigOrder;
use App\Models\PaymentGateway;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    public function pay($orderId)
    {
        $order = GigOrder::findOrFail($orderId);

        // If already paid, redirect
        if ($order->payment_status === 'paid') {
            return redirect()->route('customer.dashboard')->with('success', 'Order already paid.');
        }

        switch ($order->payment_method) {
            case 'paypal':
                return $this->processPayPal($order);
            case 'stripe':
                return $this->processStripe($order);
            case 'card':
                // Implement Generic Card or redirect to Stripe
                return $this->processStripe($order);
            default:
                // COD or others
                return redirect()->route('customer.gigs.order.success', ['order_id' => $order->id]);
        }
    }

    protected function processPayPal($order)
    {
        $gateway = PaymentGateway::where('name', 'paypal')->first();
        
        // Mock Payment for Testing/Development
        if ($gateway && isset($gateway->credentials['client_id']) && $gateway->credentials['client_id'] === 'mock_client_id') {
             $this->markAsPaid($order, 'paypal', 'MOCK_PAYPAL_' . uniqid());
             return redirect()->route('customer.gigs.order.success', ['order_id' => $order->id]);
        }

        $paypalService = new PayPalService();
        $returnUrl = route('customer.payment.paypal.callback', ['order_id' => $order->id]);
        $cancelUrl = route('customer.payment.cancel', ['order_id' => $order->id]);

        $paypalOrder = $paypalService->createOrder($order->total_amount, 'USD', $returnUrl, $cancelUrl);

        if ($paypalOrder && isset($paypalOrder['links'])) {
            foreach ($paypalOrder['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('customer.dashboard')->with('error', 'Failed to initiate PayPal payment.');
    }

    protected function processStripe($order)
    {
        $gateway = PaymentGateway::where('name', 'stripe')->first();
        if (!$gateway || empty($gateway->credentials['secret_key'])) {
            return back()->with('error', 'Stripe configuration missing.');
        }

        // Mock Payment for Testing/Development
        if (isset($gateway->credentials['publishable_key']) && $gateway->credentials['publishable_key'] === 'pk_test_mock') {
            $this->markAsPaid($order, 'stripe', 'MOCK_STRIPE_' . uniqid());
            return redirect()->route('customer.gigs.order.success', ['order_id' => $order->id]);
        }

        Stripe::setApiKey($gateway->credentials['secret_key']);

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Order #' . $order->id,
                        ],
                        'unit_amount' => (int)($order->total_amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('customer.payment.stripe.callback', ['order_id' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('customer.payment.cancel', ['order_id' => $order->id]),
            ]);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return redirect()->route('customer.dashboard')->with('error', 'Stripe Error: ' . $e->getMessage());
        }
    }

    public function paypalCallback(Request $request, $orderId)
    {
        $order = GigOrder::findOrFail($orderId);
        $paypalService = new PayPalService();

        if ($request->has('token')) {
            $capture = $paypalService->captureOrder($request->token);
            
            if ($capture && isset($capture['status']) && $capture['status'] === 'COMPLETED') {
                $this->markAsPaid($order, 'paypal', $capture['id']);
                return redirect()->route('customer.gigs.order.success', ['order_id' => $order->id]);
            }
        }

        return redirect()->route('customer.dashboard')->with('error', 'Payment failed or cancelled.');
    }

    public function stripeCallback(Request $request, $orderId)
    {
        $order = GigOrder::findOrFail($orderId);
        $gateway = PaymentGateway::where('name', 'stripe')->first();
        Stripe::setApiKey($gateway->credentials['secret_key']);

        try {
            $session = StripeSession::retrieve($request->get('session_id'));
            
            if ($session->payment_status === 'paid') {
                $this->markAsPaid($order, 'stripe', $session->payment_intent);
                return redirect()->route('customer.gigs.order.success', ['order_id' => $order->id]);
            }
        } catch (\Exception $e) {
            // Log error
        }

        return redirect()->route('customer.dashboard')->with('error', 'Payment verification failed.');
    }

    public function cancel($orderId)
    {
        return redirect()->route('customer.dashboard')->with('info', 'Payment cancelled.');
    }

    protected function markAsPaid($order, $method, $transactionId)
    {
        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $method,
            // You might want to store transaction ID in a separate column or notes
        ]);
        
        // Trigger any events like 'OrderPaid' if needed
    }
}
