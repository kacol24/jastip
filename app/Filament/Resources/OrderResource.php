<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\Widgets\OrdersOverview;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Select::make('customer_id')
                              ->label('Customer')
                              ->options(Customer::active()->get()
                                                ->pluck('name', 'id'))
                              ->searchable()
                              ->required(),
                        Select::make('status')
                              ->options([
                                  Order::STATUS_OPEN      => Order::STATUS_OPEN,
                                  Order::STATUS_CLOSED    => Order::STATUS_CLOSED,
                                  Order::STATUS_DELIVERY  => Order::STATUS_DELIVERY,
                                  Order::STATUS_SHIPPED   => Order::STATUS_SHIPPED,
                                  Order::STATUS_PENDING   => Order::STATUS_PENDING,
                                  Order::STATUS_PAID      => Order::STATUS_PAID,
                                  Order::STATUS_COMPLETED => Order::STATUS_COMPLETED,
                              ])
                              ->default(Order::STATUS_OPEN),
                    ]),
                Grid::make()
                    ->schema([
                        TextInput::make('deposit')
                                 ->numeric()
                                 ->prefix('Rp')
                                 ->mask(fn(Mask $mask) => $mask->money('', '.', 0))
                                 ->minValue(0)
                                 ->default('0'),
                        TextInput::make('shipping_fee')
                                 ->numeric()
                                 ->prefix('Rp')
                                 ->mask(
                                     fn(Mask $mask
                                     ) => $mask->money('', '.', 0)
                                 )
                                 ->minValue(0)
                                 ->default('0'),
                    ]),
                Forms\Components\RichEditor::make('notes'),
                Placeholder::make('Items'),
                Forms\Components\Repeater::make('items')
                                         ->relationship()
                                         ->schema(self::itemSchema())
                                         ->dehydrated()
                                         ->orderable()
                                         ->defaultItems(1)
                                         ->disableLabel(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label('ID')
                                         ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                                         ->label('Customer')
                                         ->sortable(),
                Tables\Columns\TextColumn::make('items_count')
                                         ->counts('items')
                                         ->label('Items')
                                         ->toggleable(),
                Tables\Columns\TextColumn::make('items_sum_quantity')
                                         ->sum('items', 'quantity')
                                         ->label('Qty.')
                                         ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                                          ->enum([
                                              'Open'            => 'Open',
                                              'Closed'          => 'Closed',
                                              'Shipping'        => 'Shipping',
                                              'Shipped'         => 'Shipped',
                                              'Pending Payment' => 'Pending Payment',
                                              'Paid'            => 'Paid',
                                              'Completed'       => 'Completed',
                                          ]),
                Tables\Columns\TextColumn::make('subtotal')
                                         ->prefix('Rp')
                                         ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('shipping_fee')
                                         ->prefix('Rp')
                                         ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('grand_total')
                                         ->prefix('Rp')
                                         ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deposit')
                                         ->prefix('Rp')
                                         ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                                         ->toggleable(),
                Tables\Columns\TextColumn::make('amount_due')
                                         ->prefix('Rp')
                                         ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                                         ->dateTime()
                                         ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('order_status')
                      ->label('Status')
                      ->action(function (Order $record, $data): void {
                          $record->status = $data['status'];
                          $record->save();
                      })
                      ->form([
                          Select::make('status')
                                ->label('Status')
                                ->options([
                                    'Open'            => 'Open',
                                    'Closed'          => 'Closed',
                                    'Shipping'        => 'Shipping',
                                    'Shipped'         => 'Shipped',
                                    'Pending Payment' => 'Pending Payment',
                                    'Paid'            => 'Paid',
                                    'Completed'       => 'Completed',
                                ])
                                ->required(),
                      ]),
                Action::make('confirmation')
                      ->label('Confirm Order')
                      ->url(fn(Order $record): string => $record->confirmation_link)
                      ->icon('heroicon-o-clipboard-list')
                      ->visible(fn(Order $record): bool => $record->status == Order::STATUS_OPEN)
                      ->openUrlInNewTab(),
                Action::make('invoice')
                      ->label('Send Invoice')
                      ->url(fn(Order $record): string => $record->invoice_link)
                      ->icon('heroicon-s-cash')
                      ->visible(fn(Order $record): bool => $record->status == Order::STATUS_CLOSED)
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view'   => Pages\ViewOrder::route('/{record}'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    protected static function itemSchema()
    {
        return [
            Grid::make()
                ->schema([
                    Grid::make()
                        ->schema([
                            Select::make('product_id')
                                  ->label('Product')
                                  ->options(Product::active()->get()->pluck('full_name', 'id'))
                                  ->searchable()
                                  ->reactive()
                                  ->afterStateUpdated(function (
                                      $state,
                                      callable $set,
                                      \Closure $get
                                  ) {
                                      $product = Product::find($state);
                                      $price = $product->price ?? 0;
                                      $fee = $product->fee ?? 0;
                                      $subtotal = ($price + $fee) * $get('quantity');

                                      $set('price', number_format($price, 0, ',', '.'));
                                      $set('fee', number_format($fee, 0, ',', '.'));
                                      $set('subtotal', number_format($subtotal, 0, ',', '.'));
                                  }),
                            TextInput::make('notes'),
                        ])
                        ->columns(2),
                ]),
            Grid::make([
                'default' => 2,
            ])->schema([
                TextInput::make('price')
                         ->disabled()
                         ->prefix('Rp')
                         ->default('0')
                         ->mask(fn(Mask $mask) => $mask->money('', '.', 0))
                         ->columnSpan(2),
                TextInput::make('fee')
                         ->disabled()
                         ->prefix('Rp')
                         ->default('0')
                         ->mask(fn(Mask $mask) => $mask->money('', '.', 0)),
                TextInput::make('discount_fee')
                         ->prefix('Rp')
                         ->default('0')
                         ->mask(fn(Mask $mask) => $mask->money('', '.', 0)),
                TextInput::make('quantity')
                         ->numeric()
                         ->reactive()
                         ->afterStateUpdated(function (
                             $state,
                             callable $set,
                             \Closure $get
                         ) {
                             $product = Product::find($get('product_id'));
                             $price = $product->price ?? 0;
                             $fee = $product->fee ?? 0;
                             $subtotal = ($price + $fee) * $state;

                             $set('price', number_format($price, 0, ',', '.'));
                             $set('fee', number_format($fee, 0, ',', '.'));
                             $set('subtotal', number_format($subtotal, 0, ',', '.'));
                         })
                         ->default(1),
                TextInput::make('line_total')
                         ->disabled()
                         ->prefix('Rp')
                         ->default('0')
                         ->mask(fn(Mask $mask) => $mask->money('', '.', 0)),
            ])
                ->columns(4),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrdersOverview::class,
        ];
    }
}
