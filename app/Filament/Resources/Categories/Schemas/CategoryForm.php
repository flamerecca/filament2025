<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('名稱')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('代稱 Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                RichEditor::make('description')
                    ->label('描述')
                    ->extraInputAttributes([
                        'style' => 'min-height: 300px;'
                    ])
                    ->columnSpanFull(),
                Select::make('parent_id')
                    ->label('父分類')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
