<?php

namespace App\Models;

use App\Models\Traits\HasToggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasToggle;

    protected $fillable = [
        'brand_id',
        'name',
        'price',
        'fee',
        'is_frozen',
        'is_handcarry',
    ];

    protected $appends = [
        'full_name',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeSearchByName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function getFullNameAttribute()
    {
        return '['.$this->brand->name.'] '.$this->name;
    }

    public function getOrderCountAttribute()
    {
        return $this->orderItems->groupBy('order_id')->count();
    }

    public function getOrderQuantityAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    public function getOrderSubtotalAttribute()
    {
        return $this->price * $this->order_quantity;
    }
}
