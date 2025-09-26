<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Produk;
use App\Models\Transaction;

class TransactionDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaction_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'produk_id',
        'quantity',
        'price'
    ];

    /**
     * Get the transaction that owns the transaction detail.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Get the product associated with the transaction detail.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
} 