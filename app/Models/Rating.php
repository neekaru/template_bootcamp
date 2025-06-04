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
        if (is_array($this->foto) && count($this->foto) > 0) {
            return Storage::url($this->foto[0]);
        }
        if (is_string($this->foto) && $this->foto) {
            return Storage::url($this->foto);
        }
        return null;
    }

    public function customer()
    {
        return $this->belongsTo(Pembeli::class);
    }

    public function product()
    {
        return $this->belongsTo(Produk::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
