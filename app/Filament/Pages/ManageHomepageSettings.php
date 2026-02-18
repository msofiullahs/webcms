<?php

namespace App\Filament\Pages;

use App\Models\Page;
use App\Settings\HomepageSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomepageSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Homepage Settings';
    protected static string $settings = HomepageSettings::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Homepage')->schema([
                Forms\Components\Select::make('homepage_page_id')
                    ->label('Homepage')
                    ->helperText('Select a page to use as your homepage. Build the page layout using the block editor.')
                    ->options(
                        Page::published()->pluck('title', 'id')->map(function ($title) {
                            if (is_array($title) || is_object($title)) {
                                $title = (array) $title;
                                return $title[app()->getLocale()] ?? $title['en'] ?? array_values($title)[0] ?? '';
                            }
                            return $title;
                        })
                    )
                    ->searchable()
                    ->nullable()
                    ->placeholder('— Select a page —'),
                Forms\Components\TextInput::make('featured_posts_count')
                    ->label('Featured Posts Count')
                    ->helperText('Number of latest posts to show below the page content. Set to 0 to hide.')
                    ->numeric()
                    ->default(6)
                    ->minValue(0)
                    ->maxValue(20),
            ]),
        ]);
    }
}
