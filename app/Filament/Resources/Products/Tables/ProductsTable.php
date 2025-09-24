<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('名稱')->searchable()->sortable(),
                TextColumn::make('sku')->label('SKU')->searchable()->sortable(),
                TextColumn::make('category.name')->label('分類')->sortable(),
                TextColumn::make('price')->label('價格')->numeric(decimalPlaces: 2)->sortable(),
                TextColumn::make('stock')->label('庫存')->numeric()->sortable(),
                IconColumn::make('active')->label('啟用')->boolean(),
            ])
            ->filters([
                Filter::make('active')
                    ->label('僅顯示啟用')
                    ->query(fn ($query) => $query->where('active', true)),
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
