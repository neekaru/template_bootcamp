<?php

namespace App\Livewire\Account\MyOrders;

use Livewire\Component;
use App\Models\Transaction;

class Index extends Component
{
    public function render()
    {
        //get all transactions without pagination
        $transactions = Transaction::query()
            ->where('pembeli_id', auth()->guard('pembeli')->user()->id)
            ->latest()
            ->get();

        return view('livewire.account.my-orders.index', compact('transactions'));
    }
}