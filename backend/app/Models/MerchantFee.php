<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantFee extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id', 'month', 'year', 'amount', 'status', 'paid_at'];

    /**
     * Relasi many-to-one dengan Merchant
     * MerchantFee dimiliki oleh satu merchant
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}