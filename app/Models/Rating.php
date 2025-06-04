<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Rating extends Model
{
    protected $fillable = [
        'pembeli_id',
        'produk_id',
        'transaction_id',
        'rating',
        'review',
        'foto_review',
    ];

    protected $casts = [
        'foto_review' => 'array',
    ];

    public function getImageUrlAttribute()
    {
        if (is_array($this->foto_review) && count($this->foto_review) > 0) {
            return Storage::url($this->foto_review[0]);
        }
        if (is_string($this->foto_review) && $this->foto_review) {
            return Storage::url($this->foto_review);
        }
        return null;
    }

    public function customer()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }

    public function product()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
