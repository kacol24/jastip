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

    public function getOrderListLinkAttribute()
    {
        $orderItems = $this->orderItems;
        $orderItemsByNotes = $this->products
            ->map(function ($product) {
                $uniqueItems = $product->orderItems->unique('notes');
                $orderItems = $product->orderItems;
                $product->orderItems = $uniqueItems->map(function ($item) use ($orderItems) {
                    $item->quantity = $orderItems->where('notes', $item->notes)->sum('quantity');

                    return $item;
                });

                return $product;
            });

        $append = view('brand.wa-order-list', [
            'orderItems' => $orderItemsByNotes,
        ])->render();

        return "https://wa.me/{$this->whatsapp_phone}?text=".urlencode($append);
    }
}
