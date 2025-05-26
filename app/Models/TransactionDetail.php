<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produk;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'produk_id',
        'qty',
        'price',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
                                                                                                                                                                                                                                                                                                                    