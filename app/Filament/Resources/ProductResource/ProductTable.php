<?php

namespace App\Filament\Resources\ProductResource;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\Builder;

final class ProductTable
{
    public static function table()
    {
        return [
            TextColumn::make('full_name')
                      ->searchable(query: function (Builder $query, string $search): Builder {
                          return $query
                              ->searchByName($search)
                              ->orWhereHas('brand', function ($query) use ($search) {
                                  return $query->searchByName($search);
                              });
                      }),
            TextColumn::make('brand.name')
                      ->toggleable(),
            TextColumn::make('price')
                      ->prefix('Rp')
                      ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
            TextColumn::make('fee')
                      ->prefix('Rp')
                      ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
        ];
    }

    public static function filter()
    {
        return [
            MultiSelectFilter::make('brand')
                             ->relationship('brand', 'name'),
        ];
    }
}
