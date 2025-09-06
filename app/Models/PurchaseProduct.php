<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'total'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
