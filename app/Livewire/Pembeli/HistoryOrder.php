<?php

namespace App\Livewire\Pembeli;

use App\Models\Cart;
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
        $transaction = Transaction::with('transactionDetails.product')
            ->where('id', $transactionId)
            ->where('pembeli_id', Auth::guard('pembeli')->id())
            ->first();

        if (!$transaction) {
            session()->flash('error', 'Transaksi tidak ditemukan.');
            return;
        }

        foreach ($transaction->transactionDetails as $detail) {
            if ($detail->product) {
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
            }
        }
        session()->flash('success', 'Produk dari pesanan telah ditambahkan kembali ke keranjang!');
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

    public function render()
    {
        $query = Transaction::with('transactionDetails.product')
            ->where('pembeli_id', Auth::guard('pembeli')->id());

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('invoice', 'like', '%' . $this->search . '%')
                  ->orWhereHas('transactionDetails.product', function ($prodQuery) {
                      $prodQuery->where('nama_produk', 'like', '%' . $this->search . '%');
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
            $query->whereHas('transactionDetails.product.category', function ($catQuery) {
                $catQuery->where('name', $this->selectedCategory);
            });
        }

        $transactions = $query->latest()->paginate(3);
        $categories = ProductCategory::orderBy('name')->pluck('name')->toArray();

        return view('livewire.pembeli.history-order', [
            'transactions' => $transactions,
            'categories' => $categories,
        ]);
    }
}
