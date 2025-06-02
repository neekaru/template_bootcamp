<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Checkout extends Component
{
    public $carts;
    public $alamat;
    public $snapToken;
    public $isProcessing = false;
    
    protected $listeners = [
        'paymentSuccess',
        'paymentPending',
        'paymentError',
        'paymentClosed'
    ];
    
    public function mount()
    {
        // Redirect if not authenticated
        if (!auth()->guard('pembeli')->check()) {
            return $this->redirect('/login', navigate: true);
        }
        
        $this->carts = Cart::where('pembeli_id', auth()->guard('pembeli')->id())
                           ->with('produk')
                           ->get();
                           
        // Redirect if cart is empty
        if ($this->carts->isEmpty()) {
            session()->flash('error', 'Your cart is empty');
            return $this->redirect('/cart', navigate: true);
        }
    }

    public function checkout()
    {
        try {
            $this->validate([
                'alamat' => 'required|string'
            ]);

            if ($this->carts->isEmpty()) {
                session()->flash('error', 'Your cart is empty');
                return;
            }

            $total = $this->carts->sum(function ($cart) {
                return $cart->produk->harga * $cart->qty;
            });

            if ($total <= 0) {
                session()->flash('error', 'Invalid total amount');
                return;
            }
            
            // Set processing flag
            $this->isProcessing = true;

            $transaction = Transaction::create([
                'pembeli_id' => auth()->guard('pembeli')->id(),
                'invoice' => 'INV-' . date('Ymd') . '-' . Str::random(5),
                'total' => $total,
                'alamat' => $this->alamat,
                'status' => 'pending'
            ]);

            // Create transaction details
            foreach ($this->carts as $cart) {
                $transaction->transactionDetails()->create([
                    'produk_id' => $cart->produk_id,
                    'quantity' => $cart->qty,
                    'price' => $cart->produk->harga
                ]);
            }

            // Get Snap Token
            $midtransService = new MidtransService();
            $snapToken = $midtransService->getSnapToken($transaction);

            if ($snapToken) {
                // Update transaction with snap token
                $transaction->update(['snap_token' => $snapToken]);
                
                // Clear cart after successful checkout
                Cart::where('pembeli_id', auth()->guard('pembeli')->id())->delete();
                
                // Set the snap token property
                $this->snapToken = $snapToken;
                
                // Log success for debugging
                Log::info('Snap token generated successfully: ' . $this->snapToken);
                
                // Dispatch the snapPay event with the token
                $this->dispatch('snapPay', snapToken: $this->snapToken);
                
                // Reset processing flag
                $this->isProcessing = false;
            } else {
                $this->isProcessing = false;
                Log::error('Failed to generate snap token');
                session()->flash('error', 'Failed to generate payment token. Please try again.');
            }
        } catch (\Exception $e) {
            $this->isProcessing = false;
            Log::error('Checkout Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            session()->flash('error', 'An error occurred during checkout: ' . $e->getMessage());
        }
    }
    
    public function paymentSuccess($result)
    {
        Log::info('Payment Success:', $result);
        session()->flash('success', 'Payment completed successfully!');
    }

    public function paymentPending($result)
    {
        Log::info('Payment Pending:', $result);
        session()->flash('info', 'Payment is pending. Please complete the payment.');
    }

    public function paymentError($result)
    {
        Log::error('Payment Error:', $result);
        session()->flash('error', 'Payment failed. Please try again.');

    }

    public function paymentClosed()
    {
        Log::info('Payment popup closed by user');
        session()->flash('info', 'Payment was cancelled. You can try again when ready.');
    }    
    
    public function render()
    {
        return view('livewire.checkout-page', [
            'snapToken' => $this->snapToken,
            'shipping_cost' => 0, // Add this since the template uses it
            'transaction' => Transaction::where('snap_token', $this->snapToken)->first()
        ]);
    }
}
