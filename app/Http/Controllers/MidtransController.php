<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('midtrans.is_production', false);
        // Set sanitization on (default)
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function getSnapToken(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
        ]);

        $transaction = Transaction::with('pembeli', 'transactionDetails.product')->findOrFail($request->transaction_id);

        // Populate items for Midtrans
        $items = [];
        foreach ($transaction->transactionDetails as $detail) {
            $items[] = [
                'id'       => $detail->product->id,
                'price'    => $detail->product->price, // Assuming your Product model has a price attribute
                'quantity' => $detail->qty,
                'name'     => $detail->product->name, // Assuming your Product model has a name attribute
            ];
        }

        // Populate customer details
        $customer_details = [
            'first_name' => $transaction->pembeli->username, // Using username as first_name
            'last_name'  => '', // Leaving last_name blank for now
            'email'      => $transaction->pembeli->email,
            // 'phone'      => $transaction->pembeli->phone, // Add phone if available
            // Add billing and shipping address if needed
        ];
        
        // Transaction details for Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $transaction->invoice,
                'gross_amount' => $transaction->total,
            ],
            'item_details'        => $items,
            'customer_details'    => $customer_details,
            // 'enabled_payments' => ['credit_card', 'gopay', 'shopeepay', 'other_qris', 'bca_va', 'bni_va', 'bri_va'] // Optional: specify enabled payment methods
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            // Update transaction with snap_token
            $transaction->snap_token = $snapToken;
            $transaction->save();

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notificationHandler(Request $request)
    {
        // TODO: Implement Midtrans notification handling
        // This will involve verifying the signature and updating the transaction status
        // based on the notification data from Midtrans.
        // Refer to Midtrans documentation for detailed implementation.
        // Example: https://docs.midtrans.com/en/payments/payment-notifications/handling-notifications
        
        // For now, just log the notification
        Log::info('Midtrans Notification: ', $request->all());
        
        return response()->json(['status' => 'ok']);
    }
} 