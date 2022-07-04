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
        'name',
        'line_total',
        'line_profit',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getNameAttribute()
    {
        return $this->product->name;
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
        return $this->quantity * $this->price;
    }

    public function getLineTotalAttribute()
    {
        return $this->subtotal + $this->line_profit;
    }

    public function getLineProfitAttribute()
    {
        return $this->quantity * $this->fee;
    }

    public function getFormattedPriceAttribute()
    {
        return $this->formatMoney($this->price);
    }

    public function getFormattedFeeAttribute()
    {
        return $this->formatMoney($this->fee);
    }

    public function getFormattedLineTotalAttribute()
    {
        return $this->formatMoney($this->line_total);
    }

    protected function formatMoney($amount)
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
