<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produk extends Model
{
    protected $table = 'produks';
    protected $fillable = ['nama_produk', 'deskripsi_produk', 'stok_tersedia', 'kategori_produk', 'ulasan_produk', 'foto', 'harga'];

    public function getImageUrlAttribute()
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }
}
