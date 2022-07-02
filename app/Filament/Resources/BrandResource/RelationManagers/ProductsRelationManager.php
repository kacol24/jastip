<?php

namespace App\Filament\Resources\BrandResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Order List';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('order_count'),
                Tables\Columns\TextColumn::make('order_quantity')
                                         ->label('Order Qty.'),
                Tables\Columns\TextColumn::make('price')
                                         ->prefix('Rp')
                                         ->formatStateUsing(function ($state) {
                                             return number_format($state, 0, ',', '.');
                                         }),
                Tables\Columns\TextColumn::make('order_subtotal')
                                         ->prefix('Rp')
                                         ->formatStateUsing(function ($state) {
                                             return number_format($state, 0, ',', '.');
                                         }),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_order')
                                     ->query(fn(Builder $query): Builder => $query->has('orderItems'))
                                     ->toggle()
                                     ->default(),
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
