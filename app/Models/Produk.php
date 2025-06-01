<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    protected $table = 'produks';
    protected $fillable = ['nama_produk', 'deskripsi_produk', 'stok_tersedia', 'kategori_produk', 'ulasan_produk', 'foto', 'harga'];
    protected $casts = [
        'foto' => 'array',
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
}
