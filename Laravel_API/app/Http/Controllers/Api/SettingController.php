<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'freelancer_payment_delay_days' => Setting::get('freelancer_payment_delay_days', 0),
            'freelancer_pending_balance_popup_text' => Setting::get('freelancer_pending_balance_popup_text', 'Your earnings are in pending status and will be available after the cooling period.'),
        ];

        return response()->json([
            'status' => true,
            'data' => $settings
        ]);
    }
}
