<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'pembeli_id',
        'produk_id',
        'transaction_id',
        'rating',
        'review',
    ];

    public function customer()
    {
        return $this->belongsTo(Pembeli::class);
    }

    public function product()
    {
        return $this->belongsTo(Produk::class);
    }
}
