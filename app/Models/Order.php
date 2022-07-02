<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_OPEN = 'Open';

    const STATUS_CLOSED = 'Closed';

    const STATUS_DELIVERY = 'Shipping';

    const STATUS_SHIPPED = 'Shipped';

    const STATUS_PENDING = 'Pending Payment';

    const STATUS_PAID = 'Paid';

    const STATUS_COMPLETED = 'Completed';

    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'deposit',
        'shipping_fee',
        'notes',
    ];

    protected $with = [
        'items',
    ];

    protected $appends = [
        'subtotal',
        'grand_total',
        'amount_due',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum('line_total');
    }

    public function getGrandTotalAttribute()
    {
        return $this->subtotal + $this->shipping_fee;
    }

    public function getAmountDueAttribute()
    {
        return $this->grand_total - $this->deposit;
    }

    public function getProfitAttribute()
    {
        return $this->items->sum('line_profit');
    }
}
