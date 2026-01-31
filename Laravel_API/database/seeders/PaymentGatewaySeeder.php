<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'paypal',
                'title' => 'PayPal',
                'credentials' => [
                    'client_id' => 'mock_client_id',
                    'client_secret' => 'mock_secret',
                    'app_id' => 'mock_app_id',
                ],
                'is_active' => true,
                'mode' => 'sandbox',
            ],
            [
                'name' => 'stripe',
                'title' => 'Stripe',
                'credentials' => [
                    'publishable_key' => 'pk_test_mock',
                    'secret_key' => 'sk_test_mock',
                    'webhook_secret' => 'whsec_mock',
                ],
                'is_active' => true,
                'mode' => 'sandbox',
            ],
            [
                'name' => 'card', // For generic credit card logic if separate
                'title' => 'Credit Card',
                'credentials' => [],
                'is_active' => true,
                'mode' => 'sandbox',
            ],
        ];

        foreach ($gateways as $gateway) {
            \App\Models\PaymentGateway::updateOrCreate(
                ['name' => $gateway['name']],
                $gateway
            );
        }
    }
}
