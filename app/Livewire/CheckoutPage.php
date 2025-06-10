<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Pembeli; // Import Pembeli model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Midtrans;
use Illuminate\Support\Facades\Log;

class CheckoutPage extends Component
{
    public $transactionId;
    public $transaction;
    public $shipping_cost = 15000;
    public $inputAlamat = ''; // Address input by user
    public $snapRedirectUrl = null;
    public $showPaymentButton = false; // Controls visibility of the final payment link

    protected $rules = [
        'inputAlamat' => 'required|string|min:10|max:255',
    ];

    public function boot()
    {
        Midtrans\Config::$serverKey = config('midtrans.server_key');
        Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        Midtrans\Config::$isSanitized = config('midtrans.is_sanitized', true);
        Midtrans\Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function mount()
    {
        if (!auth()->guard('pembeli')->check()) {
            session()->flash('error', 'Silahkan login terlebih dahulu untuk melanjutkan.');
            return $this->redirectRoute('login', navigate: true);
        }

        $cartItems = Cart::with(['produk' => function($query) {
            $query->select('id', 'nama_produk', 'harga', 'foto', 'deskripsi_produk', 'kategori_produk', 'berat');
        }])->where('pembeli_id', auth()->guard('pembeli')->id())->get();
        if ($cartItems->isEmpty()) {
            session()->flash('info', 'Keranjang Anda kosong. Silahkan tambahkan produk terlebih dahulu.');
            return $this->redirectRoute('cart.index', navigate: true);
        }
        
        // Initialize empty address
        $this->inputAlamat = '';
        
        // Pre-load transaction details for summary view if cart is not empty
        // This is a temporary transaction object for display purposes before confirmation
        $this->loadTransactionSummaryForView($cartItems);
    }
    
    protected function loadTransactionSummaryForView($cartItems)
    {
        $pembeli = auth()->guard('pembeli')->user();
        $subtotal = $cartItems->sum(fn($item) => $item->qty * $item->produk->harga);
        $total = $subtotal + $this->shipping_cost;

        // Create a temporary stdClass or array to simulate transaction for view
        $this->transaction = new \stdClass();
        $this->transaction->pembeli = $pembeli;
        $this->transaction->invoice = 'DRAFT-' . time(); // Indicate it's a draft
        $this->transaction->alamat = $this->inputAlamat; // Display current inputAlamat
        $this->transaction->total = $total;
        $this->transaction->transactionDetails = $cartItems->map(function ($cartItem) {
            $detail = new \stdClass();
            $detail->product = $cartItem->produk;
            $detail->qty = $cartItem->qty;
            $detail->price = $cartItem->produk->harga;
            return $detail;
        });
    }

    public function updatedInputAlamat($value)
    {
        // If address changes, update the summary view if needed
        if ($this->transaction) {
            $this->transaction->alamat = $value;
        }
        $this->showPaymentButton = false; // Hide payment button if address changes, requiring reconfirmation
        $this->snapRedirectUrl = null;
    }

    public $isProcessing = false;

    public function reviewOrderAndProceed()
    {
        if ($this->isProcessing) {
            return;
        }

        $this->isProcessing = true;
        
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($e->validator->errors()->has('inputAlamat')) {
                session()->flash('error', 'Alamat wajib diisi.');
            }
            $this->isProcessing = false;
            return;
        }

        $cartItems = Cart::with(['produk' => function($query) {
            $query->select('id', 'nama_produk', 'harga', 'foto', 'deskripsi_produk', 'kategori_produk', 'berat');
        }])->where('pembeli_id', auth()->guard('pembeli')->id())->get();
        if ($cartItems->isEmpty()) {
            session()->flash('error', 'Keranjang Anda kosong. Tidak dapat melanjutkan.');
            return $this->redirectRoute('cart.index', navigate: true);
        }

        try {
            $this->createTransactionInternal($cartItems);
        } catch (\Illuminate\Database\QueryException $e) {
            session()->flash('error', 'Gagal menyimpan pesanan karena masalah database: ' . $e->getMessage());
            Log::error('CheckoutPage DB Error (reviewOrderAndProceed): ' . $e->getMessage());
            return;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
            Log::error('CheckoutPage General Error (reviewOrderAndProceed): ' . $e->getMessage());
            return;
        }

        $this->isProcessing = false;

        if ($this->transactionId) {
            // Fetch the actual transaction created with proper eager loading
            $this->transaction = Transaction::with(['pembeli', 'transactionDetails.product'])->find($this->transactionId);
            if ($this->transaction) {
                $this->generateSnapRedirectUrlInternal();
                if ($this->snapRedirectUrl) {
                    $this->showPaymentButton = true; // Show the final payment button/link
                }
            } else {
                session()->flash('error', 'Transaksi tidak ditemukan setelah proses pembuatan.');
            }
        } else {
            session()->flash('error', 'Gagal membuat ID transaksi internal.');
        }
    }

    protected function createTransactionInternal($cartItems)
    {
        $pembeli = auth()->guard('pembeli')->user();
        $subtotal = $cartItems->sum(fn($item) => $item->qty * $item->produk->harga);
        $total = $subtotal + $this->shipping_cost;

        $transaction = Transaction::create([
            'pembeli_id' => $pembeli->id,
            'invoice'    => 'INV-' . time() . Str::random(5),
            'berat'      => $cartItems->sum(fn($item) => $item->qty * ($item->produk->berat ?? 100)),
            'alamat'     => $this->inputAlamat, // Use the validated inputAlamat
            'total'      => $total,
            'status'     => 'pending',
        ]);

        foreach ($cartItems as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'produk_id'     => $item->produk_id,
                'quantity'      => $item->qty,
                'price'         => $item->produk->harga,
            ]);
        }

        $this->transactionId = $transaction->id;
    }

    protected function generateSnapRedirectUrlInternal()
    {
        if (!$this->transaction) {
            Log::error('generateSnapRedirectUrlInternal called with no transaction.');
            session()->flash('error', 'Kesalahan internal: Data transaksi tidak ditemukan untuk link pembayaran.');
            return;
        }

        $items = [];
        $totalAmount = 0;
        
        foreach ($this->transaction->transactionDetails as $detail) {
            $price = intval($detail->price);
            $quantity = intval($detail->quantity);
            $subtotalItem = $price * $quantity;
            $totalAmount += $subtotalItem;
            $items[] = [
                'id'       => strval($detail->product->id),
                'price'    => $price,
                'quantity' => $quantity,
                'name'     => $detail->product->nama_produk ?? 'Produk #' . $detail->product->id,
            ];
        }

        if ($this->shipping_cost > 0) {
            $items[] = [
                'id'       => 'shipping',
                'price'    => intval($this->shipping_cost),
                'quantity' => 1,
                'name'     => 'Biaya Pengiriman',
            ];
            $totalAmount += intval($this->shipping_cost);
        }

        $customer_details = [
            'first_name' => $this->transaction->pembeli->username,
            'last_name'  => '',
            'email'      => $this->transaction->pembeli->email,
            'shipping_address' => [
                'first_name' => $this->transaction->pembeli->username,
                'address'    => $this->transaction->alamat, // This should now be the confirmed inputAlamat
            ]
        ];
        
        $finish_redirect_url = route('midtrans.payment_return');
        $unfinish_redirect_url = route('midtrans.payment_return');
        $error_redirect_url = route('midtrans.payment_return');

        $params = [
            'transaction_details' => [
                'order_id'     => $this->transaction->invoice,
                'gross_amount' => $totalAmount,
            ],
            'item_details'        => $items,
            'customer_details'    => $customer_details,
            'callbacks' => [
                'finish' => $finish_redirect_url,
                'unfinish' => $unfinish_redirect_url,
                'error' => $error_redirect_url,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 1,
            ],
        ];

        try {
            Log::info('Midtrans Create Transaction Request Params (Internal):', $params);
            $midtransTransaction = Midtrans\Snap::createTransaction($params);
            $this->snapRedirectUrl = $midtransTransaction->redirect_url;
            
            if ($this->transaction->total != $totalAmount) {
                Log::warning('Transaction total mismatch during Midtrans redirect URL generation (Internal).', [
                    'database_total' => $this->transaction->total,
                    'calculated_total' => $totalAmount,
                    'invoice' => $this->transaction->invoice
                ]);
                // Don't update the total here to prevent price reset
            }

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Redirect URL Generation Error (Internal): ' . $e->getMessage(), ['params' => $params]);
            session()->flash('error', 'Gagal mendapatkan link pembayaran: ' . $e->getMessage());
            if (app()->environment('local')) {
                session()->flash('error_details', 'Trace: ' . $e->getTraceAsString());
            }
        }
    }

    public function render()
    {
        // If the real transaction is loaded (after reviewOrderAndProceed), use it.
        // Otherwise, the summary $this->transaction from mount() is used.
        return view('livewire.checkout-page', [
            'currentTransaction' => $this->transaction // Pass it explicitly for clarity in view
        ]);
    }
}