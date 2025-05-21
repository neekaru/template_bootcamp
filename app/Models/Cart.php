<?php

namespace App\Models;

use App\Models\Pembeli;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'pembeli_id',
        'produk_id',
        'qty'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class);
    }
}
