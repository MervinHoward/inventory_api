<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'purchasing_unit_id',
        'selling_unit_id',
        'purchasing_price',
        'selling_price'
    ];

    protected $casts = [
        'purchasing_price' => 'decimal:2',
        'selling_price' => 'decimal:2'
    ];

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    public function purchaseProducts() {
        return $this->hasMany(PurchaseProduct::class);
    }

    public function saleProducts() {
        return $this->hasMany(SaleProduct::class);
    }
}
