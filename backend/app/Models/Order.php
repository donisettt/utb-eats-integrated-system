<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'merchant_id',
        'total_price',
        'delivery_location',
        'status',
        'note',
    ];

    /**
     * Relasi many-to-one dengan User
     * Order dimiliki oleh satu user (pembeli)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi many-to-one dengan Merchant
     * Order dimiliki oleh satu merchant (penjual)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Relasi one-to-many dengan OrderItem
     * Satu order dapat memiliki banyak item produk
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}