<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleProduct extends Model
{
    use SoftDeletes;

    protected $filable = [
        'sale_id',
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

    public function sale() {
        return $this->belongsTo(Sale::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
