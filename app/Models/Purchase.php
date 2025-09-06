<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'code',
        'date',
        'grand_total',
        'purchase_file',
        'payment_date'
    ];

    protected $casts = [
        'data' => 'date',
        'grand_total' => 'decimal:2',
        'payment_date' => 'date',
        'purchase_file' => 'file'
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseProducts() {
        return $this->hasMany(PurchaseProduct::class);
    }
}
