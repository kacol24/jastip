<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Brand;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('brand_id')
                                       ->label('Brand')
                                       ->options(Brand::active()->get()->pluck('name', 'id'))
                                       ->searchable(),
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('price')
                                          ->numeric()
                                          ->prefix('Rp')
                                          ->mask(
                                              fn(Forms\Components\TextInput\Mask $mask) => $mask->money('', '.', 0)
                                          )
                                          ->minValue(0)
                                          ->default('0'),
                Forms\Components\TextInput::make('fee')
                                          ->numeric()
                                          ->prefix('Rp')
                                          ->mask(
                                              fn(Forms\Components\TextInput\Mask $mask) => $mask->money('', '.', 0)
                                          )
                                          ->minValue(0)
                                          ->default(15000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                                         ->searchable(['name']),
                Tables\Columns\TextColumn::make('price')
                                         ->prefix('Rp')
                                         ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('fee')
                                         ->prefix('Rp')
                                         ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view'   => Pages\ViewProduct::route('/{record}'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
