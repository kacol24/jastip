<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort',
        'order_id',
        'product_id',
        'quantity',
        'notes',
    ];

    protected $with = [
        'product',
    ];

    protected $appends = [
        'price',
        'fee',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPriceAttribute()
    {
        return $this->product->price;
    }

    public function getFeeAttribute()
    {
        return $this->product->fee;
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * ($this->price + $this->fee);
    }
}
