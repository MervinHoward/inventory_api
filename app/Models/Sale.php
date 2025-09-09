<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'code',
        'date',
        'grand_total',
        'sale_file',
        'payment_date'
    ];

    protected $casts = [
        'date' => 'date',
        'grand_total' => 'decimal:2',
        'payment_date' => 'date'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function products() {
        return $this->hasMany(SaleProduct::class);
    }
}
