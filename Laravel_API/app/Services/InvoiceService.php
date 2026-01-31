<?php

namespace App\Services;

use App\Models\GigOrder;
use Carbon\Carbon;

class InvoiceService
{
    /**
     * Generate invoice data for an order.
     *
     * @param GigOrder $order
     * @return array
     */
    public function generateInvoiceData(GigOrder $order)
    {
        $invoiceNumber = 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        $issueDate = Carbon::parse($order->created_at)->format('d M, Y');
        $dueDate = Carbon::parse($order->created_at)->addDays(3)->format('d M, Y'); // Example due date logic

        $subtotal = $order->amount;
        $tax = 0; // Add tax logic if needed
        $total = $subtotal + $tax;

        return [
            'invoice_number' => $invoiceNumber,
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'seller' => [
                'name' => $order->provider->name,
                'email' => $order->provider->email,
                'address' => 'Provider Address Here', // You might want to fetch this from provider profile
            ],
            'buyer' => [
                'name' => $order->user->name,
                'email' => $order->user->email,
                'address' => 'Buyer Address Here', // You might want to fetch this from user profile
            ],
            'items' => [
                [
                    'description' => $order->gig->title . ' - ' . $order->package->name . ' Package',
                    'quantity' => 1,
                    'unit_price' => $order->amount,
                    'total' => $order->amount,
                ]
            ],
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => ucfirst($order->status),
            'payment_method' => $order->payment_method ?? 'COD',
        ];
    }
}
