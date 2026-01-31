<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::all();
        return view('Admin.payment_gateways.index', compact('gateways'));
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        return view('Admin.payment_gateways.edit', compact('paymentGateway'));
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'mode' => 'required|in:sandbox,live',
            'credentials' => 'nullable|array',
        ]);

        $data = $request->only(['title', 'mode']);
        $data['is_active'] = $request->has('is_active');
        
        // Clean up credentials - remove empty values
        if ($request->has('credentials')) {
            $data['credentials'] = array_filter($request->credentials, function($value) {
                return !is_null($value) && $value !== '';
            });
        }

        $paymentGateway->update($data);

        return redirect()->route('admin.payment-gateways.index')->with('success', 'Payment gateway updated successfully.');
    }
}
