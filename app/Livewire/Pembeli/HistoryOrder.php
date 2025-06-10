<?php

namespace App\Livewire\Pembeli;

use App\Models\Cart;
use Midtrans;
use App\Models\Transaction;
use App\Models\Category as ProductCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class HistoryOrder extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedCategory = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
    ];


    public function boot()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function mount()
    {
        if (!Auth::guard('pembeli')->check()) {
            return redirect()->route('login');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $this->statusFilter === $status ? '' : $status;
        $this->resetPage();
    }

    public function beliLagi($transactionId)
    {
        $transaction = Transaction::with(['transactionDetails.product' => function($query) {
                $query->whereNotNull('nama_produk')->where('nama_produk', '!=', '');
            }])
            ->where('id', $transactionId)
            ->where('pembeli_id', Auth::guard('pembeli')->id())
            ->first();

        if (!$transaction) {
            session()->flash('error', 'Transaksi tidak ditemukan.');
            return;
        }

        $addedCount = 0;
        foreach ($transaction->transactionDetails as $detail) {
            // Only add products that exist and have valid data
            if ($detail->produk_id &&
                $detail->produk_id > 0 &&
                $detail->product &&
                $detail->product->nama_produk &&
                trim($detail->product->nama_produk) !== '') {
                
                $cartItem = Cart::where('produk_id', $detail->produk_id)
                    ->where('pembeli_id', Auth::guard('pembeli')->id())
                    ->first();

                if ($cartItem) {
                    $cartItem->increment('qty', $detail->quantity);
                } else {
                    Cart::create([
                        'pembeli_id' => Auth::guard('pembeli')->id(),
                        'produk_id' => $detail->produk_id,
                        'qty' => $detail->quantity
                    ]);
                }
                $addedCount++;
            }
        }
        
        if ($addedCount > 0) {
            session()->flash('success', "Produk dari pesanan telah ditambahkan kembali ke keranjang! ({$addedCount} produk ditambahkan)");
        } else {
            session()->flash('warning', 'Tidak ada produk yang tersedia dari pesanan ini untuk ditambahkan ke keranjang.');
        }
        
        return $this->redirectRoute('cart.index', navigate: true);
    }

    public function lihatDetailPesanan($invoice)
    {
        return $this->redirectRoute('checkout.result', ['invoice' => $invoice], navigate: true);
    }

    public function reviewProduk($productId, $transactionId = null)
    {
        // User might need to have purchased the product. This can be checked in the review form mount method.
        return $this->redirectRoute('produk.review', ['productId' => $productId, 'transactionId' => $transactionId]);
    }

    public function bayarSekarang($transactionId)
    {
        $transaction = Transaction::with(['transactionDetails.product' => function($query) {
                $query->whereNotNull('nama_produk')->where('nama_produk', '!=', '');
            }], 'pembeli')->findOrFail($transactionId);
        if ($transaction->status === 'pending' && empty($transaction->snap_token)) {
            // Use the same logic as CheckoutPage for Midtrans Snap redirect URL
            $items = [];
            $totalAmount = 0;
            foreach ($transaction->transactionDetails as $detail) {
                // Only process items with valid products
                if ($detail->produk_id &&
                    $detail->produk_id > 0 &&
                    $detail->product &&
                    $detail->product->nama_produk &&
                    trim($detail->product->nama_produk) !== '') {
                    
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
            }
            
            // Check if we have any valid items to pay for
            if (empty($items)) {
                session()->flash('error', 'Tidak ada produk yang valid untuk dibayar dalam transaksi ini.');
                return;
            }
            $customer_details = [
                'first_name' => $transaction->pembeli->username,
                'last_name'  => '',
                'email'      => $transaction->pembeli->email,
                'shipping_address' => [
                    'first_name' => $transaction->pembeli->username,
                    'address'    => $transaction->alamat,
                ]
            ];
            $finish_redirect_url = route('midtrans.payment_return');
            $unfinish_redirect_url = route('midtrans.payment_return');
            $error_redirect_url = route('midtrans.payment_return');
            $params = [
                'transaction_details' => [
                    'order_id'     => $transaction->invoice,
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
                    'duration' => 24,
                ],
            ];
            try {
                $midtransTransaction = \Midtrans\Snap::createTransaction($params);
                $transaction->snap_token = $midtransTransaction->token;
                $transaction->save();
                return redirect()->away($midtransTransaction->redirect_url);
            } catch (\Exception $e) {
                // If order_id already used, generate a new one and retry
                if (str_contains($e->getMessage(), 'order_id sudah digunakan') || str_contains($e->getMessage(), 'order_id has already been taken')) {
                    $orderId = $transaction->invoice . '-' . now()->format('His');
                    $params['transaction_details']['order_id'] = $orderId;
                    $transaction->invoice = $orderId;
                    $midtransTransaction = \Midtrans\Snap::createTransaction($params);
                    $transaction->snap_token = $midtransTransaction->token;
                    $transaction->save();
                    return redirect()->away($midtransTransaction->redirect_url);
                } else {
                    session()->flash('error', 'Gagal membuat Snap Redirect URL: ' . $e->getMessage());
                    return;
                }
            }
        } else if ($transaction->snap_token) {
            // If snap_token exists, try to get the redirect_url again
            $params = [
                'transaction_details' => [
                    'order_id'     => $transaction->invoice,
                    'gross_amount' => $transaction->total,
                ],
            ];
            try {
                $midtransTransaction = \Midtrans\Snap::createTransaction($params);
                return redirect()->away($midtransTransaction->redirect_url);
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal mendapatkan link pembayaran: ' . $e->getMessage());
                return;
            }
        } else {
            session()->flash('error', 'Snap token tidak ditemukan. Silakan hubungi admin.');
            return;
        }
    }

    public function render()
    {
        $pembeliId = Auth::guard('pembeli')->id();

        // Define a reusable condition for what constitutes a valid product detail
        $validProductDetailCondition = function ($detailQuery) {
            $detailQuery->whereNotNull('produk_id')
                        ->where('produk_id', '>', 0)
                        ->whereHas('product', function ($productQuery) {
                            $productQuery->whereNotNull('nama_produk')
                                         ->where('nama_produk', '!=', '');
                        });
        };

        $query = Transaction::query()
            ->where('pembeli_id', $pembeliId)
            // Core filter: Only include transactions that have at least one valid detail.
            // This is applied BEFORE pagination.
            ->whereHas('transactionDetails', $validProductDetailCondition)
            // Eager load: For the selected transactions, load only their valid details
            // and associated products.
            ->with([
                'transactionDetails' => function ($detailLoadQuery) use ($validProductDetailCondition) {
                    // Apply the same condition to filter which details are loaded
                    $validProductDetailCondition($detailLoadQuery);
                    // And ensure the product itself is loaded for these valid details
                    $detailLoadQuery->with('product');
                },
                'pembeli' // Eager load pembeli if needed elsewhere (e.g. bayarSekarang method)
            ]);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('invoice', 'like', '%' . $this->search . '%')
                  // Search within products of (already established) valid details
                  ->orWhereHas('transactionDetails.product', function ($prodSearchQuery) {
                      $prodSearchQuery->where('nama_produk', 'like', '%' . $this->search . '%');
                        // No need to re-check for nama_produk != '' here as $validProductDetailCondition covers it.
                  });
            });
        }

        if (!empty($this->statusFilter)) {
            match ($this->statusFilter) {
                'berhasil' => $query->where('status', 'success'),
                'diproses' => $query->where('status', 'pending'),
                'gagal' => $query->whereIn('status', ['failed', 'expired', 'cancelled', 'deny', 'chargeback']),
                default => null,
            };
        }

        if (!empty($this->selectedCategory)) {
            // Filter by category of (already established) valid products within valid details
            $query->whereHas('transactionDetails.product.category', function ($catQuery) {
                $catQuery->where('name', $this->selectedCategory);
            });
        }

        $transactions = $query->latest()->paginate(3);
        
        // The post-query filtering on the collection is no longer needed here,
        // as the main query now handles the filtering of transactions
        // before pagination.

        $categories = ProductCategory::orderBy('name')->pluck('name')->toArray();

        return view('livewire.pembeli.history-order', [
            'transactions' => $transactions,
            'categories' => $categories,
        ]);
    }
}
