<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'General Settings';
    protected static string $settings = GeneralSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Site Information')->schema([
                Forms\Components\TextInput::make('site_title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('site_description')
                    ->rows(3),
                Forms\Components\Textarea::make('footer_text')
                    ->rows(2),
            ]),
            Forms\Components\Section::make('Branding')->schema([
                Forms\Components\FileUpload::make('site_logo')
                    ->image()
                    ->directory('settings'),
                Forms\Components\FileUpload::make('site_favicon')
                    ->image()
                    ->directory('settings')
                    ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/svg+xml']),
            ])->columns(2),
        ]);
    }
}
