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
        return [
            Card::make(
                'Total Revenue',
                'Rp'.number_format(Order::get()->sum('grand_total'), 0, ',', '.')
            ),
            Card::make(
                'Total Cost',
                'Rp'.number_format(OrderItem::get()->sum('subtotal'), 0, ',', '.')
            ),
            Card::make(
                'Total Profit',
                'Rp'.number_format(Order::get()->sum('profit'), 0, ',', '.')
            ),
        ];
    }
}
