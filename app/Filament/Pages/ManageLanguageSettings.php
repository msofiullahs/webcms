<?php

namespace App\Filament\Pages;

use App\Settings\LanguageSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageLanguageSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Language Settings';
    protected static string $settings = LanguageSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Language Configuration')->schema([
                Forms\Components\Select::make('default_locale')
                    ->options(fn () => \App\Models\Language::active()->pluck('name', 'code')->toArray())
                    ->required(),
                Forms\Components\Toggle::make('show_language_switcher')
                    ->label('Show language switcher on frontend'),
                Forms\Components\TagsInput::make('available_locales')
                    ->label('Available Locales')
                    ->placeholder('Add locale code (e.g., en, id, fr)')
                    ->separator(','),
            ]),
        ]);
    }
}
