<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('article')->required(),
                TextInput::make('quantity')->required(),
                TextInput::make('price')->numeric()->required(),
                TiptapEditor::make('description')
                    ->label('Content')
                    ->profile('default')
                    ->disk('public')
                    ->directory('tiptap')
                    ->maxSize(3000)
                    ->output(TiptapOutput::Html)
                    ->extraInputAttributes(['style' => 'min-height: 16rem;'])
                    ->maxContentWidth('7xl')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('images/products')
                    ->columnSpanFull(),
                TextInput::make('stock')->numeric()->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->orderByDesc('id'))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Image')
                    ->width(150)
                    ->height(150)
                    ->toggleable(),
                TextColumn::make('quantity')->sortable(),
                TextColumn::make('sku')->searchable(),
                TextColumn::make('price')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state.' $'),
                TextColumn::make('stock')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
