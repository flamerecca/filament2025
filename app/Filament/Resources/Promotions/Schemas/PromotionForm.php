<?php

namespace App\Filament\Resources\Promotions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('名稱')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('折扣型別')
                    ->options([
                        'percentage' => '百分比',
                        'fixed_amount' => '固定金額',
                    ])
                    ->required(),
                Select::make('products')
                    ->label('商品')
                    ->multiple() // 開啟多選
                    ->relationship('products', 'name'),
                TextInput::make('discount_value')
                    ->label('折扣值')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->step(0.01),
                DateTimePicker::make('starts_at')
                    ->label('開始時間')
                    ->nullable(),
                DateTimePicker::make('ends_at')
                    ->label('結束時間')
                    ->nullable(),
                Toggle::make('active')
                    ->label('啟用')
                    ->default(true),
            ]);
    }
}
