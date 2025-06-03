<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

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
        try {
            $notification = new Notification();
            
            $transaction = Transaction::where('invoice', $notification->order_id)->firstOrFail();
            $transaction_status = $notification->transaction_status;
            $fraud = $notification->fraud_status;

            Log::info('Midtrans Notification: ', [
                'order_id' => $notification->order_id,
                'transaction_status' => $transaction_status,
                'fraud_status' => $fraud,
                'payment_type' => $notification->payment_type
            ]);

            $transaction->payment_status = $transaction_status;
            
            switch ($transaction_status) {
                case 'settlement':
                    // Payment is successful and transaction is settled
                    $transaction->status = 'paid';
                    $transaction->payment_date = now();
                    break;
                
                case 'pending':
                    // Transaction is created but waiting for payment
                    $transaction->status = 'pending';
                    break;
                
                case 'deny':
                    // Payment is denied
                    $transaction->status = 'failed';
                    break;
                
                case 'expire':
                    // Transaction is expired
                    $transaction->status = 'expired';
                    break;
                
                case 'cancel':
                    // Transaction is cancelled
                    $transaction->status = 'cancelled';
                    break;
                
                case 'refund':
                case 'partial_refund':
                    // Payment is refunded
                    $transaction->status = 'refunded';
                    break;
                
                case 'chargeback':
                case 'partial_chargeback':
                    // Payment is charged back
                    $transaction->status = 'chargeback';
                    break;
            }

            $transaction->save();

            // Return response to Midtrans
            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function handlePaymentReturn(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;

        Log::info('Payment Return Handler:', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus
        ]);

        if ($statusCode == 200 && $transactionStatus == 'settlement') {
            // Payment successful
            session()->flash('success', 'Pembayaran berhasil! Terima kasih atas pesanan Anda.');
        } elseif ($statusCode == 201 && $transactionStatus == 'pending') {
            // Payment pending
            session()->flash('info', 'Menunggu pembayaran Anda. Silakan selesaikan pembayaran sesuai instruksi.');
        } else {
            // Payment failed or other status
            session()->flash('error', 'Terjadi masalah dengan pembayaran. Silakan coba lagi atau hubungi kami untuk bantuan.');
        }

        // Redirect to cart page
        return redirect()->route('cart.index');
    }
}