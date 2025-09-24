<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('分類')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('名稱')
                    ->required()
                    ->maxLength(255),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->label('描述')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->label('價格')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->step(0.01),
                TextInput::make('stock')
                    ->label('庫存')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Toggle::make('active')
                    ->label('啟用')
                    ->default(true),
            ]);
    }
}
