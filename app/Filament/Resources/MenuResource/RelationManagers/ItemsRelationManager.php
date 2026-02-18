<?php

namespace App\Filament\Resources\MenuResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'allItems';
    protected static ?string $title = 'Menu Items';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('type')
                ->options([
                    'custom' => 'Custom URL',
                    'page' => 'Page',
                    'post' => 'Post',
                ])
                ->default('custom')
                ->required()
                ->live(),
            Forms\Components\TextInput::make('url')
                ->label('URL')
                ->visible(fn (Forms\Get $get) => $get('type') === 'custom'),
            Forms\Components\Select::make('parent_id')
                ->label('Parent Item')
                ->relationship('parent', 'title')
                ->nullable()
                ->placeholder('None (Top Level)'),
            Forms\Components\Select::make('target')
                ->options([
                    '_self' => 'Same Window',
                    '_blank' => 'New Window',
                ])
                ->default('_self'),
            Forms\Components\TextInput::make('icon')
                ->placeholder('heroicon-o-home'),
            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('url')
                    ->limit(30),
                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Parent')
                    ->placeholder('Top Level'),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
