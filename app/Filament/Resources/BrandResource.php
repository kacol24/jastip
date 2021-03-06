<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $label = 'Stores';

    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'MASTER';

    protected static ?string $slug = 'stores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                          ->required(),
                Forms\Components\TextInput::make('phone')
                                          ->prefix('+62')
                                          ->tel(),
                Forms\Components\Textarea::make('address'),
                Forms\Components\RichEditor::make('information'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('order_count'),
                Tables\Columns\TextColumn::make('order_items_count'),
                Tables\Columns\TextColumn::make('order_value')
                                         ->prefix('Rp')
                                         ->formatStateUsing(function ($state) {
                                             return number_format($state, 0, ',', '.');
                                         }),
                Tables\Columns\TextColumn::make('phone')
                                         ->prefix(fn(Brand $record): string => $record->whatsapp_phone ? '+62' : '')
                                         ->url(fn(Brand $record
                                         ): string => $record->whatsapp_phone ? 'https://wa.me/'.$record->whatsapp_phone : '')
                                         ->openUrlInNewTab()
                                         ->toggleable(),
                Tables\Columns\TextColumn::make('address')
                                         ->limit(30)
                                         ->wrap()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('information')
                                         ->html()
                                         ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_order')
                                     ->query(fn(Builder $query): Builder => $query->has('orderItems'))
                                     ->toggle(),
            ])
            ->actions([
                Action::make('order_list')
                      ->label('Order List')
                      ->url(fn(Brand $record): string => $record->order_list_link)
                      ->icon('heroicon-o-clipboard-list')
                      ->openUrlInNewTab(),
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
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit'   => Pages\EditBrand::route('/{record}/edit'),
            'view'   => Pages\ViewBrand::route('/{record}'),
        ];
    }
}
