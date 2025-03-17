<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderProductResource\Pages;
use App\Models\OrderProduct;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderProductResource extends Resource
{
    protected static ?string $model = OrderProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('order.id')->label('Order ID')->sortable(),
                TextColumn::make('product.name')->label('Product')->sortable(),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                TextColumn::make('price')->label('Price')->sortable(),
            ])
            ->filters([

            ])
            ->actions([
            ])
            ->bulkActions([

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
            'index' => Pages\ListOrderProducts::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
