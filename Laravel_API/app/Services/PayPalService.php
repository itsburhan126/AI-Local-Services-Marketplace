<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\PaymentGateway;

class PayPalService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $gateway = PaymentGateway::where('name', 'paypal')->first();
        if ($gateway) {
            $this->baseUrl = $gateway->mode === 'sandbox' ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
            $this->clientId = $gateway->credentials['client_id'] ?? null;
            $this->clientSecret = $gateway->credentials['client_secret'] ?? $gateway->credentials['secret'] ?? null;
        }
    }

    protected function getAccessToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        return null;
    }

    public function createOrder($amount, $currency = 'USD', $returnUrl, $cancelUrl)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) return null;

        $response = Http::withToken($accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', ''),
                        ],
                    ],
                ],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function captureOrder($orderId)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) return null;

        $response = Http::withToken($accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture", [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
