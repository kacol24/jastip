<?php

namespace App\Models;

use App\Models\Traits\HasToggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use HasToggle;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'information',
    ];

    protected $appends = [
        'whatsapp_phone',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Product::class);
    }

    public function scopeSearchByName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function getOrderCountAttribute()
    {
        return $this->orderItems->groupBy('order_id')->count();
    }

    public function getOrderValueAttribute()
    {
        return $this->orderItems->sum('subtotal');
    }

    public function getOrderItemsCountAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    public function getWhatsappPhoneAttribute()
    {
        if (! $this->phone) {
            return '';
        }

        return '62'.$this->phone;
    }
}
