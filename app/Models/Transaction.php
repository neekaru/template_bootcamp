<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionDetail;
use App\Models\Pembeli;

class Transaction extends Model
{
    protected $fillable = [
        'pembeli_id',
        'invoice',
        'berat',
        'alamat',
        'total',
        'status',
        'snap_token',
    ];

    public function pembeli()
    {
        return $this->hasMany(Pembeli::class);
    }
    
}
