<?php

namespace App\Filament\Pages;

use App\Settings\EmailSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageEmailSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Email Settings';
    protected static string $settings = EmailSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Email Notifications')->schema([
                Forms\Components\Toggle::make('notification_enabled')
                    ->label('Enable email notifications')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('contact_notification_email')
                    ->email()
                    ->label('Notification recipient email')
                    ->placeholder('admin@example.com'),
            ]),
            Forms\Components\Section::make('Sender Information')->schema([
                Forms\Components\TextInput::make('smtp_from_name')
                    ->label('From Name')
                    ->required(),
                Forms\Components\TextInput::make('smtp_from_email')
                    ->email()
                    ->label('From Email')
                    ->required(),
            ])->columns(2),
        ]);
    }
}
