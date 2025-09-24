<?php

namespace App\Filament\Resources\Promotions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class PromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('名稱')->searchable()->sortable(),
                TextColumn::make('type')->label('型別')->badge()->sortable(),
                TextColumn::make('products.name')->label('商品')->badge()->sortable(),
                TextColumn::make('discount_value')->label('折扣值')->numeric(decimalPlaces: 2)->sortable(),
                TextColumn::make('starts_at')->label('開始')->dateTime()->sortable(),
                TextColumn::make('ends_at')->label('結束')->dateTime()->sortable(),
                IconColumn::make('active')->label('啟用')->boolean(),
            ])
            ->filters([
                Filter::make('currently_active')
                    ->label('目前有效')
                    ->query(fn ($query) => $query->currentlyActive()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
