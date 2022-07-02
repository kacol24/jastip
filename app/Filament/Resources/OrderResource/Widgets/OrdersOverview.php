<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrdersOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $orders = Order::get();
        $orderItems = OrderItem::get();

        return [
            Card::make(
                'Total Revenue',
                'Rp'.number_format($orders->sum('grand_total'), 0, ',', '.')
            ),
            Card::make(
                'Total Cost',
                'Rp'.number_format($orderItems->sum('subtotal'), 0, ',', '.')
            ),
            Card::make(
                'Total Shipping Fee',
                'Rp'.number_format($orders->sum('shipping_fee'), 0, ',', '.')
            ),
            Card::make(
                'Total Profit',
                'Rp'.number_format($orders->sum('profit'), 0, ',', '.')
            ),
        ];
    }
}
