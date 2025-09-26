<?php

namespace App\Livewire\Checkout;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
class CheckoutResult extends Component
{
    public Transaction $transaction;

    public function mount($invoice)
    {
        try {
            $this->transaction = Transaction::where('invoice', $invoice)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Transaction not found for invoice: {$invoice}", ['exception' => $e]);
            session()->flash('error', 'Transaksi tidak ditemukan.');
            return redirect()->route('dashboard'); // Or some other appropriate page
        }
    }

    public function render()
    {
        return view('livewire.checkout.checkout-result');
    }
}
