<?php

namespace App\Filament\Resources\ProductResource;

use App\Models\Brand;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;

final class ProductForm
{
    public static function schema()
    {
        return [
            Select::make('brand_id')
                  ->label('Store')
                  ->options(Brand::active()->get()->pluck('name', 'id'))
                  ->searchable(),
            TextInput::make('name'),
            Grid::make()->schema([
                TextInput::make('price')
                         ->numeric()
                         ->prefix('Rp')
                         ->mask(
                             fn(Mask $mask) => $mask->money('', '.', 0)
                         )
                         ->minValue(0)
                         ->default('0'),
                TextInput::make('fee')
                         ->numeric()
                         ->prefix('Rp')
                         ->mask(
                             fn(Mask $mask) => $mask->money('', '.', 0)
                         )
                         ->minValue(0)
                         ->default(15000),
                Checkbox::make('is_frozen')
                        ->inline(false),
                Checkbox::make('is_handcarry')
                        ->inline(false),
            ])->columns(4),
        ];
    }
}
